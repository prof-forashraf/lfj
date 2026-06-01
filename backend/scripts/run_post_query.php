<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
// Boot the app without handling a request
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Post;
use Illuminate\Support\Facades\DB;

$query = Post::query()
    ->where('status','published')
    ->where('published_at','<=', now())
    ->with(['author','categories','tags','affiliateProducts.category']);

$sql = $query->toSql();
$params = $query->getBindings();
echo "SQL: $sql\n";
echo "Bindings: ".json_encode($params)."\n";
echo "Count via Eloquent count(): ". $query->count() ."\n";
$rows = $query->limit(5)->get();
echo "Fetched rows: " . count($rows) ."\n";
foreach($rows as $r){ echo " - " . $r->id . " | " . $r->title . "\n"; }
