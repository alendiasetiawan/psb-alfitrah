<?php

use App\Const\RoleConst;
use Illuminate\Http\Request;
use App\Http\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use App\Http\Middleware\EnsureRoleUser;
use Mockery\Exception\InvalidOrderException;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
   ->withRouting(
      web: __DIR__ . '/../routes/web.php',
      commands: __DIR__ . '/../routes/console.php',
      health: '/up',
   )
   ->withMiddleware(function (Middleware $middleware) {
      $middleware->alias([
         'auth' => Authenticate::class,
         'guest' => RedirectIfAuthenticated::class,
         'role' => EnsureRoleUser::class,
      ]);
   })
   ->withExceptions(function (Exceptions $exceptions) {
      $exceptions->render(function (InvalidOrderException $e, Request $request) {
         return response()->view('livewire.web.errors.500', ['exception' => $e, 'request' => $request], 500);
      });
      $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, Request $request) {
         return response()->view('livewire.web.errors.404', ['exception' => $e, 'request' => $request], 404);
      });
      $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, Request $request) {
         if ($e->getStatusCode() === 401) {
            return response()->view('livewire.web.errors.401', ['exception' => $e, 'request' => $request], 401);
         }
      });
      $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException $e, Request $request) {
         return response()->view('livewire.web.errors.429', ['exception' => $e, 'request' => $request], 429);
      });
   })->create();
