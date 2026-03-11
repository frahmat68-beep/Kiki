<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AssetController extends Controller
{
    public function public(string $path): BinaryFileResponse
    {
        return $this->serveFile(public_path($path), public_path());
    }

    public function media(string $path): BinaryFileResponse
    {
        return $this->serveFile(storage_path('app/public/' . $path), storage_path('app/public'));
    }

    private function serveFile(string $candidatePath, string $allowedRoot): BinaryFileResponse
    {
        $realAllowedRoot = realpath($allowedRoot);
        $realCandidatePath = realpath($candidatePath);

        abort_unless(
            $realAllowedRoot !== false
            && $realCandidatePath !== false
            && str_starts_with($realCandidatePath, $realAllowedRoot . DIRECTORY_SEPARATOR)
            && is_file($realCandidatePath),
            404
        );

        return response()->file($realCandidatePath, [
            'Cache-Control' => 'public, max-age=604800, stale-while-revalidate=86400',
        ]);
    }
}
