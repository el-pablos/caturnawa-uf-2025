<?php

namespace App\Services;

use App\Models\PricingPhase;
use App\Models\Registration;
use Carbon\Carbon;

/**
 * Service untuk mengelola sistem pricing multi-fase
 * 
 * Menangani logika pricing dinamis berdasarkan:
 * - Kategori peserta (UNAS, External, High School)
 * - Fase pricing (Early Bird, Phase 1, 2, 3)
 * - Tanggal pendaftaran
 */
class PricingService
{
    /**
     * Dapatkan harga untuk kategori peserta pada tanggal tertentu
     * 
     * @param string $participantCategory
     * @param \Carbon\Carbon|null $date
     * @return array
     */
    public function getPriceForCategory($participantCategory, $date = null)
    {
        $date = $date ?? now();
        
        $phase = PricingPhase::active()
                            ->where('start_date', '<=', $date)
                            ->where('end_date', '>=', $date)
                            ->where('participant_category', $participantCategory)
                            ->first();
        
        if (!$phase) {
            return $this->getDefaultPrice($participantCategory);
        }

        return [
            'amount' => $phase->amount,
            'phase' => $phase->phase_name,
            'phase_name' => $phase->phase_display_name,
            'category' => $participantCategory,
            'category_name' => $phase->category_name,
            'start_date' => $phase->start_date,
            'end_date' => $phase->end_date,
            'description' => $phase->description,
            'is_early_bird' => $phase->phase_name === 'early_bird',
        ];
    }

    /**
     * Dapatkan harga default jika tidak ada fase yang aktif
     * 
     * @param string $participantCategory
     * @return array
     */
    private function getDefaultPrice($participantCategory)
    {
        $defaultPrices = [
            'unas_student' => 150000,
            'external_student' => 200000,
            'high_school_student' => 100000,
        ];

        $amount = $defaultPrices[$participantCategory] ?? 200000;

        return [
            'amount' => $amount,
            'phase' => 'default',
            'phase_name' => 'Harga Default',
            'category' => $participantCategory,
            'category_name' => Registration::PARTICIPANT_CATEGORIES[$participantCategory] ?? $participantCategory,
            'start_date' => null,
            'end_date' => null,
            'description' => 'Harga default untuk ' . (Registration::PARTICIPANT_CATEGORIES[$participantCategory] ?? $participantCategory),
            'is_early_bird' => false,
        ];
    }

    /**
     * Dapatkan semua fase pricing yang tersedia
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPricingPhases()
    {
        return PricingPhase::active()
                          ->orderBy('start_date', 'asc')
                          ->get()
                          ->groupBy('phase_name');
    }

    /**
     * Dapatkan fase pricing yang sedang aktif
     * 
     * @param \Carbon\Carbon|null $date
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCurrentPricingPhases($date = null)
    {
        $date = $date ?? now();
        
        return PricingPhase::active()
                          ->where('start_date', '<=', $date)
                          ->where('end_date', '>=', $date)
                          ->get()
                          ->groupBy('participant_category');
    }

    /**
     * Cek apakah sedang dalam periode early bird
     * 
     * @param \Carbon\Carbon|null $date
     * @return bool
     */
    public function isEarlyBirdPeriod($date = null)
    {
        $date = $date ?? now();
        
        return PricingPhase::active()
                          ->where('phase_name', 'early_bird')
                          ->where('start_date', '<=', $date)
                          ->where('end_date', '>=', $date)
                          ->exists();
    }

    /**
     * Dapatkan fase pricing berikutnya untuk kategori tertentu
     * 
     * @param string $participantCategory
     * @param \Carbon\Carbon|null $date
     * @return \App\Models\PricingPhase|null
     */
    public function getNextPricingPhase($participantCategory, $date = null)
    {
        $date = $date ?? now();
        
        return PricingPhase::active()
                          ->where('participant_category', $participantCategory)
                          ->where('start_date', '>', $date)
                          ->orderBy('start_date', 'asc')
                          ->first();
    }

    /**
     * Hitung estimasi penghematan jika mendaftar sekarang vs fase berikutnya
     * 
     * @param string $participantCategory
     * @param \Carbon\Carbon|null $date
     * @return array|null
     */
    public function calculateSavings($participantCategory, $date = null)
    {
        $currentPrice = $this->getPriceForCategory($participantCategory, $date);
        $nextPhase = $this->getNextPricingPhase($participantCategory, $date);
        
        if (!$nextPhase) {
            return null;
        }
        
        $savings = $nextPhase->amount - $currentPrice['amount'];
        
        if ($savings <= 0) {
            return null;
        }
        
        return [
            'current_price' => $currentPrice['amount'],
            'next_price' => $nextPhase->amount,
            'savings' => $savings,
            'savings_percentage' => round(($savings / $nextPhase->amount) * 100, 1),
            'next_phase_start' => $nextPhase->start_date,
            'days_left' => now()->diffInDays($nextPhase->start_date, false),
        ];
    }

    /**
     * Validasi kategori peserta
     * 
     * @param string $participantCategory
     * @return bool
     */
    public function isValidParticipantCategory($participantCategory)
    {
        return array_key_exists($participantCategory, Registration::PARTICIPANT_CATEGORIES);
    }

    /**
     * Dapatkan semua kategori peserta yang tersedia
     * 
     * @return array
     */
    public function getParticipantCategories()
    {
        return Registration::PARTICIPANT_CATEGORIES;
    }

    /**
     * Set harga untuk registrasi berdasarkan kategori peserta
     * 
     * @param \App\Models\Registration $registration
     * @param string $participantCategory
     * @return void
     */
    public function setPriceForRegistration(Registration $registration, $participantCategory)
    {
        if (!$this->isValidParticipantCategory($participantCategory)) {
            throw new \InvalidArgumentException("Invalid participant category: {$participantCategory}");
        }

        $priceData = $this->getPriceForCategory($participantCategory);
        
        $registration->participant_category = $participantCategory;
        $registration->amount = $priceData['amount'];
        $registration->pricing_phase = $priceData['phase'];
        $registration->original_price = $priceData['amount'];
    }

    /**
     * Dapatkan ringkasan pricing untuk semua kategori
     * 
     * @param \Carbon\Carbon|null $date
     * @return array
     */
    public function getPricingSummary($date = null)
    {
        $summary = [];
        
        foreach (Registration::PARTICIPANT_CATEGORIES as $category => $name) {
            $priceData = $this->getPriceForCategory($category, $date);
            $savings = $this->calculateSavings($category, $date);
            
            $summary[$category] = [
                'category_name' => $name,
                'current_price' => $priceData,
                'savings' => $savings,
            ];
        }
        
        return $summary;
    }
}
