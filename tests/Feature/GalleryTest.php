<?php

namespace Tests\Feature;

use App\Models\Moment;
use App\Models\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class GalleryTest extends TestCase
{
    use RefreshDatabase;

    protected function makeMoment(array $attributes = []): Moment
    {
        return Moment::create(array_merge([
            'title' => 'Kencan Pertama',
            'description' => 'Cerita kita.',
            'tags' => ['date', 'travel'],
            'moment_date' => '2026-01-15',
        ], $attributes));
    }

    protected function addPhoto(Moment $moment, string $caption = 'Foto kita'): Photo
    {
        return Photo::create([
            'moment_id' => $moment->id,
            'path' => 'photos/'.$moment->id.'/foto.jpg',
            'caption' => $caption,
            'sort_order' => 0,
            'is_cover' => true,
        ]);
    }

    public function test_gallery_page_returns_successful_response(): void
    {
        $moment = $this->makeMoment();
        $this->addPhoto($moment, 'Foto kita');

        $this->get('/galeri')->assertStatus(200)->assertSee('Foto kita');
    }

    public function test_home_filters_moments_by_tag(): void
    {
        $this->makeMoment(['title' => 'Liburan di Bali', 'tags' => ['travel']]);
        $this->makeMoment(['title' => 'Kencan di Kafe', 'tags' => ['date']]);

        $this->get('/?tag=travel')
            ->assertStatus(200)
            ->assertSee('Liburan di Bali')
            ->assertDontSee('Kencan di Kafe');
    }

    public function test_moment_show_page_renders_with_slideshow_button(): void
    {
        $moment = $this->makeMoment();
        $this->addPhoto($moment);

        $this->get('/momen/'.$moment->id)
            ->assertStatus(200)
            ->assertSee('Putar Slideshow');
    }

    public function test_like_moment_toggles_once_per_device(): void
    {
        $moment = $this->makeMoment();
        $withDevice = fn () => $this->withCookie('dk_device_id', 'device-1');

        $response = $withDevice()->post('/momen/'.$moment->id.'/like');
        $response->assertOk()
            ->assertJson(['liked' => true, 'likes' => 1]);
        $moment->refresh();
        $this->assertSame(1, $moment->likes);

        $response = $withDevice()->post('/momen/'.$moment->id.'/like');
        $response->assertOk()
            ->assertJson(['liked' => false, 'likes' => 0]);
        $moment->refresh();
        $this->assertSame(0, $moment->likes);
    }

    public function test_home_and_show_render_like_buttons(): void
    {
        $moment = $this->makeMoment();
        $this->addPhoto($moment);

        $this->get('/')->assertStatus(200)->assertSee('like-btn');
        $this->get('/momen/'.$moment->id)->assertStatus(200)->assertSee('like-btn');
    }

    public function test_admin_can_create_moment_with_tags(): void
    {
        session()->put('admin_authenticated', true);

        $this->post('/admin/momen', [
            'title' => 'Liburan Bareng',
            'description' => 'Ke pantai.',
            'tags_string' => 'Travel, Date, travel ',
            'moment_date' => '2026-08-02',
            'photos' => [UploadedFile::fake()->create('foto.jpg', 10, 'image/jpeg')],
            'captions' => ['Senja di pantai'],
        ])->assertRedirect(route('admin.dashboard'));

        $moment = Moment::where('title', 'Liburan Bareng')->firstOrFail();

        $this->assertSame(['travel', 'date'], $moment->tags);
        $this->assertSame(1, $moment->photos()->count());
    }

    public function test_admin_dashboard_shows_stats_widget(): void
    {
        $moment = $this->makeMoment();
        $this->addPhoto($moment);

        session()->put('admin_authenticated', true);

        $this->get('/admin')
            ->assertStatus(200)
            ->assertSee('Penonton unik')
            ->assertSee('Momen Paling Dilihat');
    }
}