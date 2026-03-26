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