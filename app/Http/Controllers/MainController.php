<?php

namespace App\Http\Controllers;

use App\Http\Requests\WordRequest;
use Exception;
use Faker\Core\File;
use Illuminate\Http\Request;

class MainController extends Controller
{

    public array $words = [];

    public function __construct()
    {
        $this->words = $this->readData();
    }

    /**
     * @throws Exception
     */
    public function index()
    {
        $word = $this->getRandomWord($this->words);
        return view("content")
            ->with(
                [
                    "word" => $word["word"],
                    "word_id" => $word["word_id"],
                ]
            );
    }

    private function readData(): array
    {
        $file = fopen(storage_path("app/public/file.csv"), "r");
        $words = [];

        while (!feof($file)) {
            $words[] = fgetcsv($file, 0, ";");
        }

        fclose($file);
        unset($words[0]);

        return $words;
    }

    /**
     * @throws Exception
     */
    private function getRandomWord($array): array
    {
        unset($array[1]);
        do {
            $array_id = random_int(1, max(array_keys($array)));
            if (array_key_exists($array_id, $array)) {
                $word = $array[$array_id];
                break;
            }
        } while (true);

        return [
            "word" => $word[0],
            "word_id" => $array_id
        ];
    }

    /**
     * @throws Exception
     */
    public function checkWord(WordRequest $request): \Illuminate\Http\JsonResponse
    {
        $answer = $request->get("answer");
        $wordId = $request->get("word_id");
        $oldWord = $this->words[$wordId];

        unset($this->words[$wordId]);
        $newDataArray = $this->getRandomWord($this->words);
        $returnHTML = view('components.answer')
            ->with(
                [
                    "oldWord" => $oldWord[0],
                    "answerRight" => (mb_strtolower(trim($answer)) === mb_strtolower(trim($oldWord[1])))? "green": "red"
                ]
            )
            ->render();
        return response()->json(
            [
                "newWord" => $newDataArray["word"],
                "word_id" => $newDataArray["word_id"],
                "html" => $returnHTML
            ]
        );
    }


}
