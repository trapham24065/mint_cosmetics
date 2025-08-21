<?php
/**
 * @project mint_cosmetics
 * @author PhamTra
 * @email trapham24065@gmail.com
 * @date 8/21/2025
 * @time 4:08 PM
 */

declare(strict_types=1);
namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Http\Request;

class LogSuccessfulLogin
{

    /**
     * The request instance.
     */
    protected Request $request;

    /**
     * Create the event listener.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     */
    public function handle(Authenticated $event): void
    {
        // $event->user contains the authenticated user model
        $user = $event->user;
        $user->last_login_at = now();
        $user->last_login_ip = $this->request->ip();
        $user->save();
    }

}
