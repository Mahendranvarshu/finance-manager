<?php

namespace App\Services;

use Carbon\Carbon;

class LoanCalculator
{
    /**
     * Calculate daily collection amount based on loan amount, interest, and total days
     */
    public static function calculateDailyAmount(float $loanAmount, float $interestAmount, int $totalDays): float
    {
        if ($totalDays <= 0) {
            return 0;
        }
        
        $totalAmount = $loanAmount + $interestAmount;
        return round($totalAmount / $totalDays, 2);
    }

    /**
     * Calculate total days based on start and end date
     */
    public static function calculateTotalDays(Carbon $startDate, Carbon $endDate): int
    {
        return $startDate->diffInDays($endDate) + 1;
    }

    /**
     * Calculate remaining amount for a party
     */
    public static function calculateRemainingAmount(float $loanAmount, float $interestAmount, float $collectedAmount): float
    {
        $totalAmount = $loanAmount + $interestAmount;
        return max(0, round($totalAmount - $collectedAmount, 2));
    }

    /**
     * Calculate day number based on collection date and party start date
     */
    public static function calculateDayNumber(Carbon $collectionDate, Carbon $partyStartDate): int
    {
        $days = $partyStartDate->diffInDays($collectionDate);
        return $days + 1;
    }

    /**
     * Check if loan is completed
     */
    public static function isLoanCompleted(float $loanAmount, float $interestAmount, float $collectedAmount): bool
    {
        $totalAmount = $loanAmount + $interestAmount;
        return $collectedAmount >= $totalAmount;
    }

    /**
     * Calculate completion percentage
     */
    public static function calculateCompletionPercentage(float $loanAmount, float $interestAmount, float $collectedAmount): float
    {
        $totalAmount = $loanAmount + $interestAmount;
        if ($totalAmount <= 0) {
            return 0;
        }
        
        $percentage = ($collectedAmount / $totalAmount) * 100;
        return min(100, round($percentage, 2));
    }
}

