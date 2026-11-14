<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The homepage hero slides ("Real experts. Real results." etc), editable by
     * admin under Website > Homepage Slides. Starts with the four slides that
     * were hard-coded in partials/hero-carousel.blade.php.
     */
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('tag', 60)->nullable();          // small pill above the heading
            $table->string('heading_before', 80)->nullable(); // heading text before the orange word(s)
            $table->string('heading_accent', 60)->nullable(); // the orange word(s)
            $table->string('heading_after', 80)->nullable();  // heading text after the orange word(s)
            $table->string('text', 255)->nullable();          // sentence under the heading
            $table->string('image_path')->nullable();         // relative to the web root, e.g. images/carousel/slide-1.jpg or storage/hero/x.jpg
            $table->string('image_alt')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();

        DB::table('hero_slides')->insert([
            ['sort_order' => 1, 'tag' => 'Trusted Across South Africa', 'heading_before' => 'Pest-free homes,', 'heading_accent' => 'guaranteed', 'heading_after' => null,
             'text' => 'Scheduled treatments that stop infestations at the source, so pests stay gone.',
             'image_path' => 'images/carousel/slide-1.jpg', 'image_alt' => 'SP Pest Control technician treating a home', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['sort_order' => 2, 'tag' => 'Qualified technicians', 'heading_before' => 'Real experts.', 'heading_accent' => 'Real', 'heading_after' => 'results.',
             'text' => 'Friendly, uniformed professionals who inspect, explain and treat with care.',
             'image_path' => 'images/carousel/slide-2.jpg', 'image_alt' => 'SP Pest Control technician speaking with a customer', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['sort_order' => 3, 'tag' => 'Safe for your family', 'heading_before' => 'Kid and pet-safe', 'heading_accent' => 'treatments', 'heading_after' => null,
             'text' => 'Low-toxicity, targeted methods that protect your home without the worry.',
             'image_path' => 'images/carousel/slide-3.jpg', 'image_alt' => 'A family safe at home', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['sort_order' => 4, 'tag' => 'Residential and commercial', 'heading_before' => 'Protection that', 'heading_accent' => 'lasts', 'heading_after' => null,
             'text' => 'Ongoing plans with monitoring visits and a 30-day ant-free guarantee.',
             'image_path' => 'images/carousel/slide-4.jpg', 'image_alt' => 'A happy family outside their home', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
