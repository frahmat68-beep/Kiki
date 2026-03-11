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
        $normalizedPath = ltrim($path, '/');

        $candidates = [
            [public_path('storage/' . $normalizedPath), public_path('storage')],
            [base_path('storage/app/public/' . $normalizedPath), base_path('storage/app/public')],
        ];

        foreach ($candidates as [$candidatePath, $allowedRoot]) {
            if (is_file($candidatePath)) {
                return $this->serveFile($candidatePath, $allowedRoot);
            }
        }

        abort(404);
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
