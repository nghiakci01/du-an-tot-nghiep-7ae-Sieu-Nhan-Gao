<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Maatwebsite\Excel\Facades\Excel;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function admin_can_view_dashboard_with_date_filters()
    {
        $this->actingAs($this->admin);

        // Create orders in different time periods
        Order::factory()->create(['created_at' => now()->subDays(40), 'status' => Order::STATUS_COMPLETED, 'final_total' => 100000]);
        Order::factory()->create(['created_at' => now()->subDays(5), 'status' => Order::STATUS_COMPLETED, 'final_total' => 200000]);

        $startDate = now()->subDays(10)->format('Y-m-d');
        $endDate = now()->format('Y-m-d');

        $response = $this->get(route('admin.dashboard', [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]));

        $response->assertStatus(200);
        $response->assertSee('200,000 VND'); // Should see the order from 5 days ago
        $response->assertDontSee('100,000 VND'); // Should not see the order from 40 days ago
    }

    /** @test */
    public function admin_can_export_orders_to_excel()
    {
        Excel::fake();
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.reports.orders.excel'));

        $response->assertStatus(200);
        Excel::assertDownloaded('orders-report-' . now()->subDays(30)->format('Ymd') . '-' . now()->format('Ymd') . '.xlsx');
    }

    /** @test */
    public function admin_can_export_revenue_to_pdf()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.reports.revenue.pdf'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
