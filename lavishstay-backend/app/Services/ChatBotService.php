<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ChatBotService
{
    /**
     * Get bot response by parsing a markdown file for context.
     */
    public function getResponse(string $message): ?array
    {
        $answer = $this->getResponseFromMarkdown($message);

        if ($answer) {
            return [
                'answer' => $answer,
                'faq_id' => null,
                'confidence' => 0.9, // High confidence as it's a direct match
            ];
        }

        return null; // Escalate if no relevant section is found
    }

    /**
     * Reads and parses the markdown file to find a relevant section.
     */
    private function getResponseFromMarkdown(string $message): ?string
    {
        // Use base_path() to create a relative and system-agnostic path
        $path = base_path('../lavishstay.mm.md');

        if (!File::exists($path)) {
            return 'Lỗi: Không tìm thấy tệp dữ liệu (lavishstay.mm.md).';
        }

        $content = File::get($path);
        
        // 1. Parse the markdown content into a structured array (topic => content)
        $sections = $this->parseMarkdownIntoSections($content);
        
        // 2. Find the best matching section based on the user's message
        $normalizedMessage = $this->normalizeText($message);
        $bestMatchTopic = null;
        $highestScore = 0;

        foreach ($sections as $topic => $topicContent) {
            $normalizedTopic = $this->normalizeText($topic);
            $score = $this->calculateSimilarity($normalizedMessage, $normalizedTopic);

            if ($score > $highestScore) {
                $highestScore = $score;
                $bestMatchTopic = $topic;
            }
        }

        // 3. If a sufficiently good match is found, return its content
        if ($highestScore > 0.3 && $bestMatchTopic) { // Using a threshold to avoid bad matches
            return "Về **{$bestMatchTopic}**:\n\n" . $sections[$bestMatchTopic];
        }

        return null;
    }

    /**
     * Parses markdown text into an associative array where keys are headings.
     */
    private function parseMarkdownIntoSections(string $content): array
    {
        $sections = [];
        $currentTopic = 'Thông tin chung';
        $currentContent = '';

        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            // Check for level 2 or 3 headings
            if (Str::startsWith($line, '## ') || Str::startsWith($line, '### ')) {
                // If we have content for the previous topic, save it
                if (!empty(trim($currentContent))) {
                    $sections[$currentTopic] = trim($currentContent);
                }
                // Start a new topic
                $currentTopic = trim(preg_replace('/^#+\s*/', '', $line));
                $currentContent = '';
            } else {
                // Append content to the current topic
                $currentContent .= $line . "\n";
            }
        }

        // Add the last section
        if (!empty(trim($currentContent))) {
            $sections[$currentTopic] = trim($currentContent);
        }

        return $sections;
    }

    /**
     * Normalizes text for better matching (removes accents, special chars).
     */
    private function normalizeText(string $text): string
    {
        $text = Str::lower($text);
        $text = Str::ascii($text); // Removes accents
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text);
        $text = preg_replace('/\s+/', ' ', trim($text));
        return $text;
    }

    /**
     * Calculates a similarity score between two strings based on common words.
     */
    private function calculateSimilarity(string $str1, string $str2): float
    {
        $words1 = array_unique(explode(' ', $str1));
        $words2 = array_unique(explode(' ', $str2));
        
        $intersect = count(array_intersect($words1, $words2));
        $union = count(array_unique(array_merge($words1, $words2)));

        if ($union == 0) {
            return 0;
        }

        return $intersect / $union;
    }
}