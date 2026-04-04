<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FormationController as AdminFormationController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Public\ArticleController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\FormationController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\FormationCategoryController;
use App\Http\Controllers\Admin\GroqController;

// ═══════════════════════════════════════════════════════════
// IMPORTS COMMERCIAL
// ═══════════════════════════════════════════════════════════
use App\Http\Controllers\Commercial\CommercialDashboardController;
use App\Http\Controllers\Commercial\CrmContactController;
use App\Http\Controllers\Commercial\DealController;
use App\Http\Controllers\Commercial\QuoteController;
use App\Http\Controllers\Commercial\CommercialProductController;
use App\Http\Controllers\Commercial\EmailSequenceController;
use App\Http\Controllers\Commercial\PipelineController;
use App\Http\Controllers\Commercial\ActivityController;

// ═══════════════════════════════════════════════════════════
// ROUTES PUBLIQUES
// ═══════════════════════════════════════════════════════════

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// ── La vie du centre (articles/blog) ──────────────────────
Route::get('/la-vie-du-centre', [ArticleController::class, 'index'])
    ->name('articles.index');

Route::get('/la-vie-du-centre/{slug}', [ArticleController::class, 'show'])
    ->name('articles.show');

// ── Formations ────────────────────────────────────────────
Route::get('/nos-formations', [FormationController::class, 'index'])
    ->name('formations.index');

Route::get('/nos-formations/{slug}', [FormationController::class, 'show'])
    ->name('formations.show');

Route::get('/nos-formations/{slug}/telecharger-pdf', [FormationController::class, 'downloadPdf'])
    ->name('formations.pdf');

// ── Avis ──────────────────────────────────────────────────
Route::get('/vos-avis', [ReviewController::class, 'index'])
    ->name('reviews.index');

// ── Contact ───────────────────────────────────────────────
Route::get('/contact', [ContactController::class, 'index'])
    ->name('contact');

Route::post('/contact', [ContactController::class, 'store'])
    ->name('contact.store');

// ── Pages légales ─────────────────────────────────────────
Route::get('/mentions-legales', [PageController::class, 'show'])
    ->defaults('slug', 'mentions-legales')
    ->name('mentions-legales');

Route::get('/conditions-generales-de-vente', [PageController::class, 'show'])
    ->defaults('slug', 'cgv')
    ->name('cgv');

Route::get('/conditions-generales-utilisation', [PageController::class, 'show'])
    ->defaults('slug', 'cgu')
    ->name('cgu');

Route::get('/politique-cookies', [PageController::class, 'show'])
    ->defaults('slug', 'politique-cookies')
    ->name('politique-cookies');

Route::get('/politique-confidentialite', [PageController::class, 'show'])
    ->defaults('slug', 'politique-confidentialite')
    ->name('politique-confidentialite');

// ═══════════════════════════════════════════════════════════
// AUTHENTIFICATION
// ═══════════════════════════════════════════════════════════

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])
        ->name('auth.login');

    Route::post('/connexion', [AuthController::class, 'login'])
        ->name('auth.login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [AuthController::class, 'logout'])
        ->name('auth.logout');
});

// ═══════════════════════════════════════════════════════════
// ESPACE ADMINISTRATEUR
// ═══════════════════════════════════════════════════════════

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        // ── Dashboard ─────────────────────────────────────
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        // ── IA ──────────────────────────────────────────────────────
        Route::post('/ai/generate', [GroqController::class, 'generate'])
            ->name('ai.generate');

        // ── Formations ────────────────────────────────────
        Route::get('/formations', [AdminFormationController::class, 'index'])
            ->name('formations.index');

        Route::get('/formations/creer', [AdminFormationController::class, 'create'])
            ->name('formations.create');

        Route::post('/formations', [AdminFormationController::class, 'store'])
            ->name('formations.store');

        // ── Catégories de formation ────────────────────────────
        Route::get('/formations/categories', [FormationCategoryController::class, 'index'])
            ->name('formations.categories.index');

        Route::post('/formations/categories', [FormationCategoryController::class, 'store'])
            ->name('formations.categories.store');

        Route::put('/formations/categories/{formationCategory}', [FormationCategoryController::class, 'update'])
            ->name('formations.categories.update');

        Route::delete('/formations/categories/{formationCategory}', [FormationCategoryController::class, 'destroy'])
            ->name('formations.categories.destroy');

        Route::post('/formations/categories/reorder', [FormationCategoryController::class, 'reorder'])
            ->name('formations.categories.reorder');

        Route::get('/formations/{formation}/modifier', [AdminFormationController::class, 'edit'])
            ->name('formations.edit');

        Route::put('/formations/{formation}', [AdminFormationController::class, 'update'])
            ->name('formations.update');

        Route::delete('/formations/{formation}', [AdminFormationController::class, 'destroy'])
            ->name('formations.destroy');

        Route::patch('/formations/{formation}/publier', [AdminFormationController::class, 'togglePublish'])
            ->name('formations.publish');

            // ── Sessions de formation ──────────────────────────────
        Route::get('/formations/{formation}/sessions', [SessionController::class, 'index'])
            ->name('formations.sessions.index');

        Route::post('/formations/{formation}/sessions', [SessionController::class, 'store'])
            ->name('formations.sessions.store');

        Route::put('/formations/{formation}/sessions/{session}', [SessionController::class, 'update'])
            ->name('formations.sessions.update');

        Route::delete('/formations/{formation}/sessions/{session}', [SessionController::class, 'destroy'])
            ->name('formations.sessions.destroy');

        Route::patch('/formations/{formation}/sessions/{session}/toggle', [SessionController::class, 'toggle'])
            ->name('formations.sessions.toggle');

        // ── Articles ──────────────────────────────────────
        Route::get('/articles', [AdminArticleController::class, 'index'])
            ->name('articles.index');

        Route::get('/articles/creer', [AdminArticleController::class, 'create'])
            ->name('articles.create');

        Route::post('/articles', [AdminArticleController::class, 'store'])
            ->name('articles.store');

        Route::get('/articles/{article}/modifier', [AdminArticleController::class, 'edit'])
            ->name('articles.edit');

        Route::put('/articles/{article}', [AdminArticleController::class, 'update'])
            ->name('articles.update');

        Route::delete('/articles/{article}', [AdminArticleController::class, 'destroy'])
            ->name('articles.destroy');

        // ── Avis ──────────────────────────────────────────
        Route::get('/avis', [AdminReviewController::class, 'index'])
            ->name('reviews.index');

        Route::patch('/avis/{review}/approuver', [AdminReviewController::class, 'approve'])
            ->name('reviews.approve');

        Route::patch('/avis/{review}/rejeter', [AdminReviewController::class, 'reject'])
            ->name('reviews.reject');

        Route::patch('/avis/{review}/vedette', [AdminReviewController::class, 'toggleFeatured'])
            ->name('reviews.featured');

        Route::delete('/avis/{review}', [AdminReviewController::class, 'destroy'])
            ->name('reviews.destroy');

        // ── Formulaires de contact ─────────────────────────
        Route::get('/contacts', [AdminContactController::class, 'index'])
            ->name('contacts.index');

        Route::get('/contacts/{contact}', [AdminContactController::class, 'show'])
            ->name('contacts.show');

        Route::patch('/contacts/{contact}/replique', [AdminContactController::class, 'markReplied'])
            ->name('contacts.replied');

        Route::patch('/contacts/{contact}/note', [AdminContactController::class, 'addNote'])
            ->name('contacts.note');

        Route::delete('/contacts/{contact}', [AdminContactController::class, 'destroy'])
            ->name('contacts.destroy');

        // ── Utilisateurs ──────────────────────────────────
        Route::get('/utilisateurs', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/utilisateurs/creer', [UserController::class, 'create'])
            ->name('users.create');

        Route::post('/utilisateurs', [UserController::class, 'store'])
            ->name('users.store');

        Route::get('/utilisateurs/{user}/modifier', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/utilisateurs/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::delete('/utilisateurs/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy');

        Route::patch('/utilisateurs/{user}/activer', [UserController::class, 'toggleActive'])
            ->name('users.toggle');

        // ── Rôles ─────────────────────────────────────────
        Route::get('/roles', [RoleController::class, 'index'])
            ->name('roles.index');

        Route::get('/roles/creer', [RoleController::class, 'create'])
            ->name('roles.create');

        Route::post('/roles', [RoleController::class, 'store'])
            ->name('roles.store');

        Route::get('/roles/{role}/modifier', [RoleController::class, 'edit'])
            ->name('roles.edit');

        Route::put('/roles/{role}', [RoleController::class, 'update'])
            ->name('roles.update');

        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])
            ->name('roles.destroy');

        // ── Logs ──────────────────────────────────────────
        Route::get('/logs', [LogController::class, 'index'])
            ->name('logs.index');

        // ── Profil ────────────────────────────────────────
        Route::get('/profil', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::put('/profil', [ProfileController::class, 'update'])
            ->name('profile.update');
            
    });


    // ═══════════════════════════════════════════════════════════
// ESPACE COMMERCIAL
// ═══════════════════════════════════════════════════════════

Route::prefix('commercial')
    ->name('commercial.')
    ->middleware(['auth', 'commercial'])
    ->group(function () {

        // ── Dashboard ─────────────────────────────────────
        Route::get('/', [CommercialDashboardController::class, 'index'])
            ->name('dashboard');

        // ── Pipeline Kanban ────────────────────────────────
        Route::get('/pipeline', [PipelineController::class, 'index'])
            ->name('pipeline');
        Route::patch('/pipeline/{deal}/move', [PipelineController::class, 'move'])
            ->name('pipeline.move');

        // ── Contacts CRM ──────────────────────────────────
        Route::get('/contacts', [CrmContactController::class, 'index'])
            ->name('contacts.index');
        Route::get('/contacts/creer', [CrmContactController::class, 'create'])
            ->name('contacts.create');
        Route::post('/contacts', [CrmContactController::class, 'store'])
            ->name('contacts.store');
        Route::get('/contacts/{contact}', [CrmContactController::class, 'show'])
            ->name('contacts.show');
        Route::get('/contacts/{contact}/modifier', [CrmContactController::class, 'edit'])
            ->name('contacts.edit');
        Route::put('/contacts/{contact}', [CrmContactController::class, 'update'])
            ->name('contacts.update');
        Route::delete('/contacts/{contact}', [CrmContactController::class, 'destroy'])
            ->name('contacts.destroy');
        Route::post('/contacts/import-leads', [CrmContactController::class, 'importFromLeads'])
            ->name('contacts.import');

        // ── Deals ─────────────────────────────────────────
        Route::get('/deals', [DealController::class, 'index'])
            ->name('deals.index');
        Route::post('/deals', [DealController::class, 'store'])
            ->name('deals.store');
        Route::get('/deals/{deal}', [DealController::class, 'show'])
            ->name('deals.show');
        Route::put('/deals/{deal}', [DealController::class, 'update'])
            ->name('deals.update');
        Route::delete('/deals/{deal}', [DealController::class, 'destroy'])
            ->name('deals.destroy');

        // ── Activités ──────────────────────────────────────
        Route::post('/activites', [ActivityController::class, 'store'])
            ->name('activities.store');
        Route::patch('/activites/{activity}/done', [ActivityController::class, 'markDone'])
            ->name('activities.done');
        Route::delete('/activites/{activity}', [ActivityController::class, 'destroy'])
            ->name('activities.destroy');

        // ── Devis ─────────────────────────────────────────
        Route::get('/devis', [QuoteController::class, 'index'])
            ->name('quotes.index');
        Route::get('/devis/creer', [QuoteController::class, 'create'])
            ->name('quotes.create');
        Route::post('/devis', [QuoteController::class, 'store'])
            ->name('quotes.store');
        Route::get('/devis/{quote}', [QuoteController::class, 'show'])
            ->name('quotes.show');
        Route::get('/devis/{quote}/modifier', [QuoteController::class, 'edit'])
            ->name('quotes.edit');
        Route::put('/devis/{quote}', [QuoteController::class, 'update'])
            ->name('quotes.update');
        Route::delete('/devis/{quote}', [QuoteController::class, 'destroy'])
            ->name('quotes.destroy');
        Route::post('/devis/{quote}/envoyer', [QuoteController::class, 'send'])
            ->name('quotes.send');
        Route::patch('/devis/{quote}/statut', [QuoteController::class, 'updateStatus'])
            ->name('quotes.status');
        Route::get('/devis/{quote}/pdf', [QuoteController::class, 'downloadPdf'])
            ->name('quotes.pdf');

        // ── Produits ──────────────────────────────────────
        Route::get('/produits', [CommercialProductController::class, 'index'])
            ->name('products.index');
        Route::post('/produits', [CommercialProductController::class, 'store'])
            ->name('products.store');
        Route::put('/produits/{product}', [CommercialProductController::class, 'update'])
            ->name('products.update');
        Route::delete('/produits/{product}', [CommercialProductController::class, 'destroy'])
            ->name('products.destroy');
        Route::post('/produits/sync-formations', [CommercialProductController::class, 'syncFromFormations'])
            ->name('products.sync');

        // ── Séquences email ────────────────────────────────
        Route::get('/sequences', [EmailSequenceController::class, 'index'])
            ->name('sequences.index');
        Route::post('/sequences', [EmailSequenceController::class, 'store'])
            ->name('sequences.store');
        Route::get('/sequences/{sequence}', [EmailSequenceController::class, 'show'])
            ->name('sequences.show');
        Route::put('/sequences/{sequence}', [EmailSequenceController::class, 'update'])
            ->name('sequences.update');
        Route::delete('/sequences/{sequence}', [EmailSequenceController::class, 'destroy'])
            ->name('sequences.destroy');
        Route::post('/sequences/{sequence}/steps', [EmailSequenceController::class, 'addStep'])
            ->name('sequences.steps.store');
        Route::put('/sequences/{sequence}/steps/{step}', [EmailSequenceController::class, 'updateStep'])
            ->name('sequences.steps.update');
        Route::delete('/sequences/{sequence}/steps/{step}', [EmailSequenceController::class, 'destroyStep'])
            ->name('sequences.steps.destroy');
        Route::post('/sequences/{sequence}/declencher/{contact}', [EmailSequenceController::class, 'trigger'])
            ->name('sequences.trigger');
    });