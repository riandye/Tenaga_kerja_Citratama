<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;

class chatbotController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        $botman->hears('{message}', function (BotMan $botman, $message) {
            if (strtolower($message) == 'hi') {
                $this->startConversation($botman);
            } else {
                $botman->reply("Please type 'hi' to start.");
            }
        });

        $botman->listen();
    }

    public function startConversation(BotMan $botman)
    {
        $botman->startConversation(new RecruitmentConversation());
    }
}

class RecruitmentConversation extends Conversation
{
    public function run()
    {
        $this->askName();
    }

    public function askName()
    {
        $this->ask("Hello! siapa nama kamu?", function (Answer $answer) {
            $name = $answer->getText();
            $this->say("Senang bertemu dengan, $name!");
            $this->askQuestion();
        });
    }

    public function askQuestion()
    {
        $this->ask("Apa yang bisa saya bantu hari ini? kamu bisa bertanya tentang jadwal, pakaian, dokumen yang diperlukan, atau pertanyaan yang lain.", function (Answer $answer) {
            $question = strtolower($answer->getText());

            // Dapatkan semua FAQ
            $faqs = FAQ::all();
            $matchedFaq = null;

            // Periksa setiap FAQ untuk kecocokan kata kunci
            foreach ($faqs as $faq) {
                $keywords = explode(',', $faq->keyword);
                foreach ($keywords as $keyword) {
                    if (strpos($question, trim($keyword)) !== false) {
                        $matchedFaq = $faq;
                        break 2; // Keluar dari kedua loop
                    }
                }
            }

            if ($matchedFaq) {
                $this->say($matchedFaq->answer);
            } else {
                $this->say("Saya minta maaf, saya tidak dapat mengerti pertanyaannya. kamu bisa bertanya tentang jadwal, pakaian, dokumen yang diperlukan.");
            }

            $this->askMoreQuestions();
        });
    }

    public function askMoreQuestions()
    {
        $this->ask("Apakah kamu punya pertanyaan yang lain terkait dengan recruitment?", function (Answer $answer) {
            $response = strtolower($answer->getText());

            if (in_array($response, ['yes', 'y', 'iya', 'ya'])) {
                $this->askQuestion();
            } else {
                $this->say("Semoga beruntung dalam mengikuti proses recruitment, semangat!");
            }
        });
    }
}
