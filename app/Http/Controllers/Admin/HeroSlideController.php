<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Website > Homepage Slides: admin edits the wording (and optionally the photo)
 * of each slide in the homepage hero carousel, hides/shows slides and sets their order.
 */
class HeroSlideController extends Controller
{
    public function index()
    {
        return view('admin.slides.index', [
            'slides' => HeroSlide::ordered()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.slides.form', ['slide' => new HeroSlide(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);

        $slide = new HeroSlide($data);
        $slide->sort_order = (int) HeroSlide::max('sort_order') + 1;
        $slide->image_path = $this->storePhoto($request);
        $slide->save();

        return redirect()->route('admin.slides.index')->with('status', 'Slide added. It is now on the homepage.');
    }

    public function edit(HeroSlide $slide)
    {
        return view('admin.slides.form', compact('slide'));
    }

    public function update(Request $request, HeroSlide $slide)
    {
        $data = $this->validated($request, false);

        $slide->fill($data);

        if ($request->hasFile('photo')) {
            $old = $slide->isUploadedImage() ? $slide->image_path : null;
            $slide->image_path = $this->storePhoto($request);
            if ($old) {
                Storage::disk('public')->delete(substr($old, strlen('storage/')));
            }
        }

        $slide->save();

        return redirect()->route('admin.slides.index')->with('status', 'Slide saved. The homepage now shows the new wording.');
    }

    public function toggle(HeroSlide $slide)
    {
        if ($slide->is_active && HeroSlide::where('is_active', true)->count() <= 1) {
            return back()->with('error', 'At least one slide must stay on the homepage.');
        }

        $slide->update(['is_active' => ! $slide->is_active]);

        return back()->with('status', $slide->is_active ? 'Slide is showing on the homepage again.' : 'Slide hidden from the homepage. You can show it again any time.');
    }

    /** Move a slide one place up or down in the carousel order. */
    public function move(Request $request, HeroSlide $slide)
    {
        $direction = $request->input('direction') === 'up' ? 'up' : 'down';
        $slides = HeroSlide::ordered()->get()->values();
        $index = $slides->search(fn ($s) => $s->id === $slide->id);
        $swapWith = $direction === 'up' ? $index - 1 : $index + 1;

        if ($index !== false && isset($slides[$swapWith])) {
            $reordered = $slides->all();
            [$reordered[$index], $reordered[$swapWith]] = [$reordered[$swapWith], $reordered[$index]];
            foreach ($reordered as $position => $s) {
                $s->update(['sort_order' => $position + 1]);
            }
        }

        return back();
    }

    public function destroy(HeroSlide $slide)
    {
        if (HeroSlide::count() <= 1) {
            return back()->with('error', 'The homepage needs at least one slide.');
        }

        if ($slide->is_active && HeroSlide::where('is_active', true)->count() <= 1) {
            return back()->with('error', 'This is the only slide showing on the homepage. Show another slide first.');
        }

        if ($slide->isUploadedImage()) {
            Storage::disk('public')->delete(substr($slide->image_path, strlen('storage/')));
        }

        $slide->delete();

        return redirect()->route('admin.slides.index')->with('status', 'Slide deleted.');
    }

    private function validated(Request $request, bool $creating): array
    {
        $data = $request->validate([
            'tag' => 'nullable|string|max:40',
            'heading_before' => 'nullable|string|max:60',
            'heading_accent' => 'nullable|string|max:40',
            'heading_after' => 'nullable|string|max:60',
            'text' => 'nullable|string|max:180',
            'image_alt' => 'nullable|string|max:150',
            'photo' => ($creating ? 'required' : 'nullable').'|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'photo.required' => 'Please choose a photo for the new slide.',
            'photo.image' => 'The photo must be a JPG, PNG or WebP image.',
            'photo.mimes' => 'The photo must be a JPG, PNG or WebP image.',
            'photo.max' => 'The photo must not be larger than 5 MB.',
        ]);

        if (! trim(($data['heading_before'] ?? '').($data['heading_accent'] ?? '').($data['heading_after'] ?? ''))) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'heading_before' => 'Please enter a heading for the slide.',
            ]);
        }

        unset($data['photo']);

        return $data;
    }

    /** Saves an uploaded photo to storage/app/public/hero and returns the web path, e.g. storage/hero/abc.jpg. */
    private function storePhoto(Request $request): ?string
    {
        if (! $request->hasFile('photo')) {
            return null;
        }

        $path = $request->file('photo')->store('hero', 'public');

        return $path ? 'storage/'.$path : null;
    }
}
