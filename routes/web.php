<?php

use App\Http\Controllers\Document\DocumentController;
use App\Http\Controllers\Grammar\GrammarLessonController;
use App\Http\Controllers\Listening\ListeningController;
use App\Http\Controllers\Listening\ListeningMergerController;
use App\Http\Controllers\Reading\ReadingAnalyzerController;
use App\Models\Document\Document;
use Illuminate\Support\Facades\Route;
// 
use App\Http\Controllers\AssessmentController;
//
use App\Http\Controllers\VocabularyController;
//
use App\Http\Controllers\Grammar\GrammarController;
use App\Http\Controllers\Grammar\GrammarCategoryController;
use App\Http\Controllers\Grammar\GrammarTopicController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Assessment
Route::resource('assessments', AssessmentController::class, [
    'names' => [
        'index' => 'assessments.index',
        'create' => 'assessments.create',
        'store' => 'assessments.store',
        'show' => 'assessments.show',
        'edit' => 'assessments.edit',
        'update' => 'assessments.update',
        'destroy' => 'assessments.destroy',
    ]
]);

// Assessment Management Panel (Admin)
Route::prefix('assessments-admin')->name('assessments.admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AssessmentController::class, 'adminIndex'])->name('index');
});

// Lấy đề thi                                                
Route::get('/assessments/{id}', [AssessmentController::class, 'show'])->name('assessments.show');

// Làm đề thi    
Route::get('/assessments/{id}/do', [AssessmentController::class, 'doExam'])->name('assessments.do');

// Gửi bài thi
Route::post('/assessments/{id}/submit', [AssessmentController::class, 'submit'])->name('assessments.submit');

// Kết quả
Route::get('/assessments/{id}/result', [AssessmentController::class, 'result'])->name('assessments.result');

// Route bổ sung cho summary
Route::get('/assessments/{id}/summary', [AssessmentController::class, 'summary'])
    ->name('assessments.summary');

// Export PDF    
Route::get('/assessments/{id}/export', [AssessmentController::class, 'exportResult'])
    ->name('assessments.export');


// Vocabulary 
Route::resource('vocabulary', VocabularyController::class, [
    'names' => [
        'index' => 'vocabulary.index',
        'create' => 'vocabulary.create',
        'store' => 'vocabulary.store',
        'show' => 'vocabulary.show',
        'edit' => 'vocabulary.edit',
        'update' => 'vocabulary.update',
        'destroy' => 'vocabulary.destroy',
    ]
]);

// Vocabulary Review & Recall Quiz Routes
Route::get('/vocabulary/{id}/review', [VocabularyController::class, 'review'])->name('vocabulary.review');
Route::post('/vocabulary/{id}/submit-review', [VocabularyController::class, 'submitReview'])->name('vocabulary.submit-review');
Route::get('/vocabulary/reviews/{id}', [VocabularyController::class, 'showReview'])->name('vocabulary.reviews.show');

// Vocabulary Management Panel (Admin)
Route::prefix('vocabulary-admin')->name('vocabulary.admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [VocabularyController::class, 'adminIndex'])->name('index');
    
    // Stages CRUD
    Route::post('/stages', [VocabularyController::class, 'storeStage'])->name('stages.store');
    Route::post('/stages/{id}/update', [VocabularyController::class, 'updateStage'])->name('stages.update');
    Route::delete('/stages/{id}', [VocabularyController::class, 'destroyStage'])->name('stages.destroy');
    
    // Words CRUD
    Route::post('/words', [VocabularyController::class, 'storeWord'])->name('words.store');
    Route::post('/words/{id}/update', [VocabularyController::class, 'updateWord'])->name('words.update');
    Route::delete('/words/{id}', [VocabularyController::class, 'destroyWord'])->name('words.destroy');
    
    // Properties CRUD
    Route::post('/properties', [VocabularyController::class, 'storeProperty'])->name('properties.store');
    Route::post('/properties/{id}/update', [VocabularyController::class, 'updateProperty'])->name('properties.update');
    Route::delete('/properties/{id}', [VocabularyController::class, 'destroyProperty'])->name('properties.destroy');
});

// Grammar
Route::resource('grammar', GrammarController::class, [
    'names' => [
        'index' => 'grammar.index',
        'create' => 'grammar.create',
        'store' => 'grammar.store',
        'show' => 'grammar.show',
        'edit' => 'grammar.edit',
        'update' => 'grammar.update',
        'destroy' => 'grammar.destroy',
    ]
]);

//
Route::get('/grammar/lesson/{id}', [GrammarLessonController::class, 'show'])->name('grammar.lesson.show');
Route::post('/grammar/lesson/{id}/save-html', [GrammarLessonController::class, 'saveHtmlContent'])->name('grammar.lesson.save-html');

// Chức năng ôn tập & xem chi tiết bài ôn
Route::get('/grammar/lessons/{id}/review', [GrammarLessonController::class, 'review'])->name('grammar.lessons.review');
Route::post('/grammar/lessons/{id}/review', [GrammarLessonController::class, 'submitReview'])->name('grammar.lessons.submit-review');
Route::get('/grammar/reviews/{id}', [GrammarLessonController::class, 'showReview'])->name('grammar.reviews.show');

// Chức năng theo dõi tiến trình học từng phần kiến thức (Quá trình học một kiến thức)
Route::post('/grammar/lesson/{id}/progress', [GrammarLessonController::class, 'saveProgress'])->name('grammar.lesson.progress.save');
Route::post('/grammar/lesson/{id}/progress/delete', [GrammarLessonController::class, 'deleteProgress'])->name('grammar.lesson.progress.delete');
Route::post('/grammar/lesson/{id}/progress/reset', [GrammarLessonController::class, 'resetProgress'])->name('grammar.lesson.progress.reset');

// Grammar Management Panel (Admin)
Route::prefix('grammar-admin')->name('grammar.admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [GrammarController::class, 'adminIndex'])->name('index');
    Route::resource('categories', GrammarCategoryController::class)->except(['index', 'create', 'show', 'edit']);
    Route::resource('topics', GrammarTopicController::class)->except(['index', 'create', 'show', 'edit']);
    Route::resource('lessons', GrammarLessonController::class)->except(['show']);
});


// Listening — Full CRUD
Route::resource('listening', ListeningController::class, [
    'names' => [
        'index'   => 'listening.index',
        'create'  => 'listening.create',
        'store'   => 'listening.store',
        'show'    => 'listening.show',
        'edit'    => 'listening.edit',
        'update'  => 'listening.update',
        'destroy' => 'listening.destroy',
    ]
]);

// Listening — Audio Merger
Route::get('/listening-merger', [ListeningMergerController::class, 'index'])->name('listening.merger.index');
Route::post('/listening-merger/save', [ListeningMergerController::class, 'store'])->name('listening.merger.store');

// Listening Management Panel (Admin)
Route::prefix('listening-admin')->name('listening.admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [ListeningController::class, 'adminIndex'])->name('index');
});

// Listening — Session & Assessment
Route::post('/listening/{id}/session/start', [ListeningController::class, 'startSession'])
    ->name('listening.session.start');
Route::post('/listening/{id}/assessment/submit', [ListeningController::class, 'submitAssessment'])
    ->name('listening.assessment.submit');
Route::get('/listening/{id}/result', [ListeningController::class, 'result'])
    ->name('listening.result');

// Listening — Legacy tree view
Route::get('/listening/tree/{id}', [ListeningController::class, 'showDocumentTree'])
    ->name('listening.tree');

// Document — Full CRUD
Route::resource('document', DocumentController::class, [
    'names' => [
        'index'   => 'document.index',
        'create'  => 'document.create',
        'store'   => 'document.store',
        'show'    => 'document.show',
        'edit'    => 'document.edit',
        'update'  => 'document.update',
        'destroy' => 'document.destroy',
    ]
]);

// Document Management Panel (Admin)
Route::prefix('document-admin')->name('document.admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DocumentController::class, 'adminIndex'])->name('index');
});

// API: Lấy danh sách chương theo tài liệu (cho form select)
Route::get('/api/document/{id}/parts', function (int $id) {
    $parts = \App\Models\Document\DocumentPart::where('document_id', $id)
        ->orderBy('order')->get(['id', 'name', 'order']);
    return response()->json($parts);
})->name('api.document.parts');

// Reading Analyzer
Route::prefix('reading-analyzer')->name('reading.analyzer.')->group(function () {
    Route::get('/', [ReadingAnalyzerController::class, 'index'])->name('index');
    Route::post('/upload', [ReadingAnalyzerController::class, 'upload'])->name('upload');
    Route::post('/save-words', [ReadingAnalyzerController::class, 'saveWords'])->name('saveWords');
    Route::post('/export-docx', [ReadingAnalyzerController::class, 'exportDocx'])->name('exportDocx');
    Route::post('/export-csv', [ReadingAnalyzerController::class, 'exportCsv'])->name('exportCsv');

    // History
    Route::get('/history', [ReadingAnalyzerController::class, 'historyIndex'])->name('history');
    Route::delete('/history/{id}', [ReadingAnalyzerController::class, 'historyDestroy'])->name('history.destroy');

    // Filter Management
    Route::get('/filters', [ReadingAnalyzerController::class, 'filterIndex'])->name('filter.index');
    Route::post('/filters', [ReadingAnalyzerController::class, 'filterStore'])->name('filter.store');
    Route::post('/filters/{id}/update', [ReadingAnalyzerController::class, 'filterUpdate'])->name('filter.update');
    Route::delete('/filters/{id}', [ReadingAnalyzerController::class, 'filterDestroy'])->name('filter.destroy');
});


// User Smart Profile & Analytics Dashboard
Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
Route::post('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/roadmap/toggle', [\App\Http\Controllers\ProfileController::class, 'toggleRoadmapStep'])->name('profile.roadmap.toggle');
Route::get('/roadmap', [\App\Http\Controllers\ProfileController::class, 'showRoadmap'])->name('roadmap.index');

// Achievements Admin CRUD Panel
Route::prefix('achievements-admin')->name('achievements.admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AchievementAdminController::class, 'index'])->name('index');
    Route::post('/store', [\App\Http\Controllers\Admin\AchievementAdminController::class, 'store'])->name('store');
    Route::post('/{id}/update', [\App\Http\Controllers\Admin\AchievementAdminController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\Admin\AchievementAdminController::class, 'destroy'])->name('destroy');
});

// Phân hệ quản lý cây phân cấp động (Polymorphic Hierarchy Tree Module)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/hierarchy', [\App\Http\Controllers\Hierarchy\HierarchyController::class, 'index'])->name('hierarchy.index');
    Route::post('/admin/hierarchy', [\App\Http\Controllers\Hierarchy\HierarchyController::class, 'store'])->name('hierarchy.store');
    Route::get('/admin/hierarchy/{id}/manage', [\App\Http\Controllers\Hierarchy\HierarchyController::class, 'manage'])->name('hierarchy.manage');
    Route::get('/admin/hierarchy/{id}/export-pdf', [\App\Http\Controllers\Hierarchy\HierarchyController::class, 'exportPdf'])->name('hierarchy.export-pdf');
    
    // APIs cho thao tác nút (Nodes)
    Route::post('/admin/hierarchy/nodes', [\App\Http\Controllers\Hierarchy\HierarchyController::class, 'storeNode'])->name('hierarchy.nodes.store');
    Route::put('/admin/hierarchy/nodes/{id}', [\App\Http\Controllers\Hierarchy\HierarchyController::class, 'updateNode'])->name('hierarchy.nodes.update');
    Route::delete('/admin/hierarchy/nodes/{id}', [\App\Http\Controllers\Hierarchy\HierarchyController::class, 'destroyNode'])->name('hierarchy.nodes.destroy');
    Route::post('/admin/hierarchy/{id}/save-structure', [\App\Http\Controllers\Hierarchy\HierarchyController::class, 'saveStructure'])->name('hierarchy.save-structure');

    // Quản lý Phiên Bản (Versioning)
    Route::post('/admin/hierarchy/{id}/version', [\App\Http\Controllers\Hierarchy\HierarchyController::class, 'saveVersion'])->name('hierarchy.version.store');
    Route::post('/admin/hierarchy/version/{version_id}/restore', [\App\Http\Controllers\Hierarchy\HierarchyController::class, 'restoreVersion'])->name('hierarchy.version.restore');
});

// User Authentication System (Login, Register, Logout)
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::any('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');



