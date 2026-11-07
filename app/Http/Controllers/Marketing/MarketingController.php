<?php

declare(strict_types=1);

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Support\MarketingCatalog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class MarketingController extends Controller
{
    public function home(): View
    {
        return view('marketing.home', [
            'features' => array_slice(MarketingCatalog::features(), 0, 6),
        ]);
    }

    public function features(): View
    {
        return view('marketing.features.index', [
            'features' => MarketingCatalog::features(),
        ]);
    }

    public function feature(string $key): View
    {
        $feature = MarketingCatalog::feature($key);

        if ($feature === null) {
            throw new NotFoundHttpException;
        }

        return view('marketing.features.show', compact('feature'));
    }

    public function solutionIndustry(string $industry): View
    {
        $industries = MarketingCatalog::industries();

        if (! isset($industries[$industry])) {
            throw new NotFoundHttpException;
        }

        $preset = MarketingCatalog::industryPreset($industry);
        $moduleKeys = $preset?->defaultModules() ?? [];
        $modules = collect($moduleKeys)
            ->map(fn (string $key) => MarketingCatalog::module($key))
            ->filter()
            ->values()
            ->all();

        return view('marketing.solutions.industry', [
            'industry' => $industry,
            'title' => $industries[$industry],
            'preset' => $preset,
            'modules' => $modules,
        ]);
    }

    public function solutionRole(string $role): View
    {
        $roles = MarketingCatalog::roles();

        if (! isset($roles[$role])) {
            throw new NotFoundHttpException;
        }

        $moduleKeys = MarketingCatalog::roleModuleKeys($role);
        $modules = collect($moduleKeys)
            ->map(fn (string $key) => MarketingCatalog::module($key))
            ->filter()
            ->values()
            ->all();

        return view('marketing.solutions.role', [
            'role' => $role,
            'title' => $roles[$role],
            'modules' => $modules,
        ]);
    }

    public function howItWorks(): View
    {
        return view('marketing.how-it-works');
    }

    public function modules(): View
    {
        return view('marketing.modules');
    }

    public function pricing(): View
    {
        return view('marketing.pricing', [
            'plans' => config('plans.plans', []),
            'tierPrices' => config('plans.tier_prices', []),
        ]);
    }

    public function integrations(): View
    {
        $module = MarketingCatalog::module('integrations-hub');

        return view('marketing.static', [
            'title' => 'Integrations',
            'heading' => $module['name'] ?? 'Integrations Hub',
            'lead' => $module['description'] ?? 'Connect commerce, ERP, and carrier systems.',
        ]);
    }

    public function roiCalculator(): View
    {
        return view('marketing.roi-calculator');
    }

    public function sustainabilityCalculator(): View
    {
        return view('marketing.sustainability-calculator');
    }

    public function track(Request $request): View
    {
        $rma = null;
        $error = null;

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'number' => ['required', 'string'],
                'email' => ['required', 'email'],
            ]);

            $rma = \App\Models\Rma::query()
                ->withoutGlobalScopes()
                ->where('number', $validated['number'])
                ->whereHas('customer', fn ($q) => $q->where('email', $validated['email']))
                ->with(['statusHistories', 'items.product'])
                ->first();

            if ($rma === null) {
                $error = 'No RMA found for that number and email.';
            }
        }

        return view('marketing.track', compact('rma', 'error'));
    }

    public function returnsPortal(): View
    {
        return view('marketing.returns-entry');
    }

    public function developers(): View
    {
        $api = MarketingCatalog::module('api-webhooks');

        return view('marketing.developers', ['apiModule' => $api]);
    }

    public function docsApi(): View
    {
        return view('marketing.docs-api');
    }

    public function help(): View
    {
        return view('marketing.help', [
            'articles' => MarketingCatalog::helpArticles(),
        ]);
    }

    public function blog(): View
    {
        $posts = \App\Models\BlogPost::query()
            ->published()
            ->whereNull('tenant_id')
            ->latest('published_at')
            ->paginate(12);

        return view('marketing.blog', [
            'posts' => $posts,
        ]);
    }

    public function blogShow(\App\Models\BlogPost $post): View
    {
        if ($post->published_at === null || $post->published_at->isFuture()) {
            throw new NotFoundHttpException;
        }

        return view('marketing.blog-show', ['post' => $post]);
    }

    public function page(string $slug): View
    {
        $pages = [
            'changelog' => ['Changelog', 'Release history for Returns Platform modules and APIs.'],
            'roadmap' => ['Roadmap', 'In-flight capabilities tracked against config/modules.php registry.'],
            'about' => ['About', 'Built for inspection, disposition, and restocking workflows.'],
            'careers' => ['Careers', 'Join teams building modular returns operations software.'],
            'security' => ['Security', 'Tenant isolation, audit logging, and encrypted credentials.'],
            'privacy' => ['Privacy', 'How shopper and operator data is processed per tenant.'],
            'terms' => ['Terms', 'SaaS terms for Returns Platform subscriptions.'],
            'cookies' => ['Cookies', 'Cookie categories used on the marketing site.'],
            'sla' => ['SLA', 'Uptime targets for enterprise plans.'],
            'status' => ['Status', 'Operational health for APIs, portal, and admin panels.'],
        ];

        if (! isset($pages[$slug])) {
            throw new NotFoundHttpException;
        }

        [$heading, $lead] = $pages[$slug];

        return view('marketing.static', [
            'title' => $heading,
            'heading' => $heading,
            'lead' => $lead,
        ]);
    }
}
