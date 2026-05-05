<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentItemController extends Controller
{
    private array $typeConfig = [
        'testimonials'  => ['label' => 'شهادات العملاء',         'type' => 'testimonial',    'page' => 'home',          'fields' => ['title','subtitle','body','color','meta.rating','meta.avatar_letter']],
        'services'      => ['label' => 'الخدمات (الرئيسية)',      'type' => 'service',        'page' => 'home',          'fields' => ['title','body','icon','color','image','link']],
        'service-details'=>['label' => 'الخدمات (صفحة خدماتنا)', 'type' => 'service_detail', 'page' => 'services',      'fields' => ['title','subtitle','body','icon','color','meta.items','meta.btn_text']],
        'stats'         => ['label' => 'الإحصائيات',             'type' => 'stat',           'page' => 'home',          'fields' => ['title','subtitle','icon','color']],
        'features'      => ['label' => 'الميزات (شريط السمات)',   'type' => 'feature',        'page' => 'home',          'fields' => ['title','body','icon','color']],
        'steps'         => ['label' => 'خطوات "كيف يعمل"',       'type' => 'step',           'page' => 'home',          'fields' => ['title','body','icon','color']],
        'values'        => ['label' => 'القيم',                   'type' => 'value',          'page' => 'global',        'fields' => ['title','body','icon','color']],
        'goals'         => ['label' => 'الأهداف الاستراتيجية',    'type' => 'goal',           'page' => 'global',        'fields' => ['title','body','color','meta.sub_goals']],
        'team'          => ['label' => 'الفريق',                  'type' => 'team',           'page' => 'about',         'fields' => ['title','body','image']],
        'about-values'  => ['label' => 'قيم صفحة "من نحن"',      'type' => 'about_value',    'page' => 'about',         'fields' => ['title','body','icon','color','image']],
        'process-steps' => ['label' => 'خطوات الاستشارات',        'type' => 'process_step',   'page' => 'consultations', 'fields' => ['title','body','color']],
    ];

    public function index(string $type)
    {
        $config = $this->getConfig($type);
        $items = ContentItem::ofType($config['type'])
            ->forPage($config['page'])
            ->ordered()
            ->get();
        return view('admin.content.index', compact('items', 'type', 'config'));
    }

    public function create(string $type)
    {
        $config = $this->getConfig($type);
        return view('admin.content.create', compact('type', 'config'));
    }

    public function store(string $type, Request $request)
    {
        $config = $this->getConfig($type);
        $data = $this->buildData($request, $config);
        $data['type'] = $config['type'];
        $data['page'] = $config['page'];
        $data['order'] = ContentItem::ofType($config['type'])->forPage($config['page'])->max('order') + 1;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('content', 'public');
        }

        ContentItem::create($data);
        return redirect()->route('admin.content.index', $type)->with('success', 'تم إضافة العنصر بنجاح.');
    }

    public function edit(string $type, int $id)
    {
        $config = $this->getConfig($type);
        $item = ContentItem::findOrFail($id);
        return view('admin.content.edit', compact('item', 'type', 'config'));
    }

    public function update(string $type, int $id, Request $request)
    {
        $config = $this->getConfig($type);
        $item = ContentItem::findOrFail($id);
        $data = $this->buildData($request, $config);

        if ($request->hasFile('image')) {
            if ($item->image && !str_starts_with($item->image, 'http')) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('content', 'public');
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->input('image_url');
        }

        $item->update($data);
        return redirect()->route('admin.content.index', $type)->with('success', 'تم تحديث العنصر بنجاح.');
    }

    public function destroy(string $type, int $id)
    {
        $item = ContentItem::findOrFail($id);
        if ($item->image && !str_starts_with($item->image, 'http')) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        return back()->with('success', 'تم حذف العنصر بنجاح.');
    }

    public function reorder(string $type, Request $request)
    {
        $ids = $request->input('ids', []);
        foreach ($ids as $order => $id) {
            ContentItem::where('id', $id)->update(['order' => $order + 1]);
        }
        return response()->json(['success' => true]);
    }

    public function toggle(string $type, int $id)
    {
        $item = ContentItem::findOrFail($id);
        $item->update(['is_active' => !$item->is_active]);
        return back()->with('success', $item->is_active ? 'تم تفعيل العنصر.' : 'تم تعطيل العنصر.');
    }

    private function getConfig(string $type): array
    {
        return $this->typeConfig[$type] ?? ['label' => $type, 'type' => $type, 'page' => 'home', 'fields' => ['title','body']];
    }

    private function buildData(Request $request, array $config): array
    {
        $data = [
            'title'     => $request->input('title'),
            'subtitle'  => $request->input('subtitle'),
            'body'      => $request->input('body'),
            'icon'      => $request->input('icon'),
            'color'     => $request->input('color'),
            'link'      => $request->input('link'),
            'is_active' => $request->boolean('is_active', true),
        ];

        // Build meta from meta.* fields
        $meta = [];
        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'meta_')) {
                $metaKey = substr($key, 5);
                // Handle arrays like sub_goals
                if (is_string($value) && str_contains($value, "\n")) {
                    $meta[$metaKey] = array_filter(array_map('trim', explode("\n", $value)));
                } else {
                    $meta[$metaKey] = $value;
                }
            }
        }
        if (!empty($meta)) {
            $data['meta'] = $meta;
        }

        return array_filter($data, fn($v) => $v !== null);
    }
}
