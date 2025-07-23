<?php

namespace App\Services;

use App\Models\Faq;
use Illuminate\Support\Str;

class ChatBotService
{
    /**
     * Get bot response for user message
     */
    public function getResponse(string $message): ?array
    {
        $message = $this->normalizeMessage($message);
        
        // Search for matching FAQ
        $faq = $this->findBestMatch($message);
        
        if ($faq && $this->calculateConfidence($message, $faq) >= 0.6) {
            // Increment usage count
            $faq->incrementUsage();
            
            return [
                'answer' => $faq->answer,
                'faq_id' => $faq->id,
                'confidence' => $this->calculateConfidence($message, $faq),
            ];
        }
        
        return null;
    }

    /**
     * Normalize message for better matching
     */
    private function normalizeMessage(string $message): string
    {
        // Convert to lowercase
        $message = Str::lower($message);
        
        // Remove special characters
        $message = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $message);
        
        // Remove extra spaces
        $message = preg_replace('/\s+/', ' ', trim($message));
        
        return $message;
    }

    /**
     * Find best matching FAQ
     */
    private function findBestMatch(string $message): ?Faq
    {
        $faqs = Faq::active()->orderBy('priority')->get();
        $bestMatch = null;
        $bestScore = 0;

        foreach ($faqs as $faq) {
            $score = $this->calculateConfidence($message, $faq);
            
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMatch = $faq;
            }
        }

        return $bestMatch;
    }

    /**
     * Calculate confidence score between message and FAQ
     */
    private function calculateConfidence(string $message, Faq $faq): float
    {
        $questionScore = $this->calculateSimilarity($message, Str::lower($faq->question));
        
        $keywordScore = 0;
        if ($faq->keywords) {
            foreach ($faq->keywords as $keyword) {
                if (Str::contains($message, Str::lower($keyword))) {
                    $keywordScore += 0.3;
                }
            }
        }
        
        // Combine scores
        $totalScore = ($questionScore * 0.7) + min($keywordScore, 0.3);
        
        return min($totalScore, 1.0);
    }

    /**
     * Calculate similarity between two strings
     */
    private function calculateSimilarity(string $str1, string $str2): float
    {
        // Simple word matching algorithm
        $words1 = explode(' ', $str1);
        $words2 = explode(' ', $str2);
        
        $matches = 0;
        $totalWords = max(count($words1), count($words2));
        
        foreach ($words1 as $word1) {
            if (strlen($word1) < 3) continue; // Skip short words
            
            foreach ($words2 as $word2) {
                if (Str::contains($word2, $word1) || Str::contains($word1, $word2)) {
                    $matches++;
                    break;
                }
            }
        }
        
        return $totalWords > 0 ? $matches / $totalWords : 0;
    }

    /**
     * Get suggested responses based on context
     */
    public function getSuggestedResponses(string $context): array
    {
        $suggestions = [
            'greeting' => [
                'Xin chào! Tôi có thể giúp gì cho bạn?',
                'Chào bạn! Bạn cần hỗ trợ gì không?',
            ],
            'booking' => [
                'Bạn có thể đặt phòng trực tuyến tại website của chúng tôi.',
                'Để đặt phòng, vui lòng truy cập trang đặt phòng hoặc gọi hotline.',
            ],
            'policy' => [
                'Bạn có thể xem chính sách của chúng tôi tại trang chính sách.',
                'Chính sách hủy phòng và hoàn tiền được quy định rõ ràng.',
            ],
            'contact' => [
                'Bạn có thể liên hệ với chúng tôi qua hotline hoặc email.',
                'Đội ngũ hỗ trợ của chúng tôi luôn sẵn sàng giúp đỡ bạn.',
            ],
        ];

        // Determine context and return appropriate suggestions
        $detectedContext = $this->detectContext($context);
        
        return $suggestions[$detectedContext] ?? $suggestions['greeting'];
    }

    /**
     * Detect context from message
     */
    private function detectContext(string $message): string
    {
        $message = Str::lower($message);
        
        if (Str::contains($message, ['xin chào', 'hello', 'hi', 'chào'])) {
            return 'greeting';
        }
        
        if (Str::contains($message, ['đặt phòng', 'booking', 'reservation', 'book'])) {
            return 'booking';
        }
        
        if (Str::contains($message, ['chính sách', 'policy', 'quy định', 'điều khoản'])) {
            return 'policy';
        }
        
        if (Str::contains($message, ['liên hệ', 'contact', 'hotline', 'phone'])) {
            return 'contact';
        }
        
        return 'general';
    }
}
