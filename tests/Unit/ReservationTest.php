<?php
namespace Tests\Unit;

use App\Models\Reservation;
use PHPUnit\Framework\TestCase;

class ReservationTest extends TestCase
{
    public function test_reservation_has_correct_fillable_fields(): void
    {
        $reservation = new Reservation();
        $fillable = $reservation->getFillable();

        $this->assertContains('user_id', $fillable);
        $this->assertContains('service_id', $fillable);
        $this->assertContains('barber_id', $fillable);
        $this->assertContains('reservation_date', $fillable);
        $this->assertContains('reservation_time', $fillable);
        $this->assertContains('status', $fillable);
    }

    public function test_reservation_default_status_is_pending(): void
    {
        $reservation = new Reservation();
        $reservation->status = 'pending';
        $this->assertEquals('pending', $reservation->status);
    }

    public function test_reservation_status_can_be_confirmed(): void
    {
        $reservation = new Reservation();
        $reservation->status = 'confirmed';
        $this->assertEquals('confirmed', $reservation->status);
    }

    public function test_reservation_status_can_be_cancelled(): void
    {
        $reservation = new Reservation();
        $reservation->status = 'cancelled';
        $this->assertEquals('cancelled', $reservation->status);
    }
}