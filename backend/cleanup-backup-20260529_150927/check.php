require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Filament\Facades\Filament;
$resources = Filament::getResources();
$errors = [];
foreach ($resources as $resource) {
    try {
        $form = new \Filament\Forms\Form(app(\Filament\Forms\Contracts\HasForms::class));
        // Actually, we need a Livewire component to instantiate Form. 
        // Just calling reflection to load classes is enough to catch missing classes.
        $reflection = new ReflectionClass($resource);
    } catch (\Throwable $e) {
        $errors[] = $resource . ' - ' . $e->getMessage();
    }
}
if (empty($errors)) { echo 'NO_ERRORS'; } else { print_r($errors); }
