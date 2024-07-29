<?php

// app/Http/Controllers/WordGuessController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\WordGuessed;

class WordGuessController extends Controller
{
    protected $targetWord = 'react'; // Example target word
    protected $guesses = [];

    public function submitGuess(Request $request)
    {
        $guess = $request->input('guess');
        $feedback = $this->getFeedback($guess);

        $this->guesses[] = ['guess' => $guess, 'feedback' => $feedback];

        broadcast(new WordGuessed($this->targetWord, $this->guesses));

        return response()->json(['success' => true]);
    }

    protected function getFeedback($guess)
    {
        $feedback = [];
        $targetArray = str_split($this->targetWord);
        $guessArray = str_split($guess);

        // First pass for correct positions
        foreach ($guessArray as $index => $letter) {
            if ($letter === $targetArray[$index]) {
                $feedback[$index] = 'correct';
                $targetArray[$index] = null;
            }
        }

        // Second pass for incorrect positions
        foreach ($guessArray as $index => $letter) {
            if (!isset($feedback[$index]) && in_array($letter, $targetArray)) {
                $feedback[$index] = 'misplaced';
                $targetArray[array_search($letter, $targetArray)] = null;
            } elseif (!isset($feedback[$index])) {
                $feedback[$index] = 'incorrect';
            }
        }

        return $feedback;
    }
}
