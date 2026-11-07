<?php

declare(strict_types=1);

use App\Http\Controllers\Demo\DemoLoginController;
use App\Http\Controllers\Resale\ResaleStorefrontController;
use App\Http\Controllers\Marketing\ContactController;
use App\Http\Controllers\Marketing\LocaleController;
use App\Http\Controllers\Marketing\MarketingController;
use App\Http\Controllers\Marketing\SitemapController;
use App\Http\Controllers\Portal\PrintLabelController;
use App\Livewire\Marketing\TenantTrialRegister;
use App\Livewire\Portal\StartReturn;
use App\Livewire\Portal\TrackReturn;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', LocaleController::class)->name('locale.switch');

Route::get('/robots.txt', function () {
    $sitemap = url('/sitemap.xml');

    return response("User-agent: *\nAllow: /\nSitemap: {$sitemap}\n", 200, [
        'Content-Type' => 'text/plain',
    ]);
});

Route::get('/sitemap.xml', SitemapController::class)->name('marketing.sitemap');

Route::get('/', [MarketingController::class, 'home'])->name('marketing.home');
Route::get('/features', [MarketingController::class, 'features'])->name('marketing.features');
Route::get('/features/{key}', [MarketingController::class, 'feature'])->name('marketing.features.show');
Route::get('/solutions/{industry}', [MarketingController::class, 'solutionIndustry'])->name('marketing.solutions.industry');
Route::get('/solutions/role/{role}', [MarketingController::class, 'solutionRole'])->name('marketing.solutions.role');
Route::get('/how-it-works', [MarketingController::class, 'howItWorks'])->name('marketing.how-it-works');
Route::get('/modules', [MarketingController::class, 'modules'])->name('marketing.modules');
Route::get('/pricing', [MarketingController::class, 'pricing'])->name('marketing.pricing');
Route::get('/integrations', [MarketingController::class, 'integrations'])->name('marketing.integrations');
Route::get('/roi-calculator', [MarketingController::class, 'roiCalculator'])->name('marketing.roi');
Route::get('/sustainability-calculator', [MarketingController::class, 'sustainabilityCalculator'])->name('marketing.co2');
Route::match(['get', 'post'], '/track', [MarketingController::class, 'track'])->name('marketing.track');
Route::get('/returns', [MarketingController::class, 'returnsPortal'])->name('marketing.returns');
Route::get('/developers', [MarketingController::class, 'developers'])->name('marketing.developers');
Route::get('/docs/api', [MarketingController::class, 'docsApi'])->name('marketing.docs.api');
Route::get('/help', [MarketingController::class, 'help'])->name('marketing.help');
Route::get('/contact', [ContactController::class, 'show'])->name('marketing.contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('marketing.contact.store');

Route::get('/blog', [MarketingController::class, 'blog'])->name('marketing.blog');
Route::get('/blog/{post}', [MarketingController::class, 'blogShow'])->name('marketing.blog.show');

foreach (['changelog', 'roadmap', 'about', 'careers', 'security', 'privacy', 'terms', 'cookies', 'sla', 'status'] as $slug) {
    Route::get('/'.$slug, fn () => app(MarketingController::class)->page($slug))->name('marketing.'.$slug);
}

Route::redirect('/login', '/app/login')->name('login');

Route::get('/register', TenantTrialRegister::class)->name('marketing.register');

Route::middleware('demo.mode')->group(function (): void {
    Route::get('/demo', [DemoLoginController::class, 'index'])->name('demo.index');
    Route::post('/demo/login', [DemoLoginController::class, 'login'])->name('demo.login');
});

Route::prefix('resale')->name('resale.')->middleware('module:resale-marketplace')->group(function (): void {
    Route::get('/', [ResaleStorefrontController::class, 'index'])->name('index');
    Route::get('/listings/{listing}', [ResaleStorefrontController::class, 'show'])->name('show');
});

Route::prefix('portal')->name('portal.')->middleware('throttle:portal-start')->group(function (): void {
    Route::get('/', StartReturn::class)->name('start');
    Route::get('/track', TrackReturn::class)->name('track');
    Route::get('/label/{rma}', PrintLabelController::class)->name('label.print');
});
