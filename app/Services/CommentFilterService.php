<?php

namespace App\Services;

class CommentFilterService
{
    /**
     * List of forbidden keywords.
     * In a real app, this might come from a DB or config.
     */
    protected array $forbiddenKeywords = [
        'anjing', 'babi', 'monyet', 'bangsat', 'tolol', 'goblok', // Common insults
        'sara', 'rasis', 'komunis', 'liberal', 'sesat',           // Keywords related to SARA
        'pki', 'teroris', 'bunuh', 'mati',                        // Hate speech/violence
    ];

    /**
     * Check if the content contains forbidden keywords.
     * 
     * @param string $content
     * @return bool
     */
    public function containsForbiddenWords(string $content): bool
    {
        $content = strtolower($content);

        foreach ($this->forbiddenKeywords as $word) {
            if (str_contains($content, $word)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the list of detected words for logging or feedback.
     */
    public function getDetectedKeywords(string $content): array
    {
        $content = strtolower($content);
        $detected = [];

        foreach ($this->forbiddenKeywords as $word) {
            if (str_contains($content, $word)) {
                $detected[] = $word;
            }
        }

        return $detected;
    }
}
