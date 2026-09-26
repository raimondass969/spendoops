<?php

use App\Services\Csrf;

require_once __DIR__ . '/../../bootstrap.php';

$token = Csrf::generateToken();
?>

<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prisijungti | SpendOops</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/output.css">
</head>

<body class="login-page font-manrope">
    <div class="login-orbit login-orbit-left" aria-hidden="true"></div>
    <div class="login-planet login-planet-left" aria-hidden="true"></div>
    <div class="login-orbit login-orbit-right" aria-hidden="true"></div>
    <div class="login-planet login-planet-right" aria-hidden="true"></div>
    <div class="login-orbit login-orbit-bottom" aria-hidden="true"></div>

    <main class="relative z-10 flex min-h-screen w-full items-center justify-center px-4 py-8 sm:px-6 sm:py-12">
        <section class="login-card w-full max-w-[44rem] rounded-[1.75rem] px-6 py-10 sm:px-12 sm:py-14 lg:px-16 lg:py-16">
            <div class="mb-10 flex items-center justify-center gap-3 sm:mb-14">
                <img src="../assets/images/spendoops-mark.svg" alt="" class="h-12 w-12 sm:h-14 sm:w-14">
                <span class="text-2xl font-bold tracking-tight text-slate-50 sm:text-3xl">
                    Spend<span class="text-teal-300">Oops</span>
                </span>
            </div>

            <header class="mb-10 text-center sm:mb-12">
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl">Sveiki sugrįžę</h1>
                <p class="mx-auto mt-4 max-w-lg text-base leading-relaxed text-slate-400 sm:text-xl">
                    Prisijunkite ir toliau valdykite savo finansus aiškiai ir užtikrintai.
                </p>
            </header>

            <form method="POST" action="login.php">
                <input type="hidden" name="csrf_token" value="<?php echo $token ?>">

                <div class="space-y-5">
                    <div>
                        <label class="sr-only" for="login_email">El. paštas</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                class="pointer-events-none absolute left-5 top-1/2 size-7 -translate-y-1/2 text-slate-300">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                            <input class="login-input w-full rounded-2xl py-4 pl-16 pr-5 text-base text-slate-100 outline-none sm:text-lg"
                                id="login_email" type="email" name="email" placeholder="El. paštas"
                                autocomplete="email" required>
                        </div>
                    </div>

                    <div>
                        <label class="sr-only" for="login_password">Slaptažodis</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" aria-hidden="true"
                                class="pointer-events-none absolute left-5 top-1/2 size-7 -translate-y-1/2 text-slate-300">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <input class="login-input w-full rounded-2xl py-4 pl-16 pr-5 text-base text-slate-100 outline-none sm:text-lg"
                                id="login_password" type="password" name="password" placeholder="Slaptažodis"
                                autocomplete="current-password" required>
                        </div>
                    </div>
                </div>

                <button class="login-submit mt-8 flex w-full items-center justify-center gap-4 rounded-2xl px-6 py-4 text-lg font-extrabold text-slate-950 sm:text-xl"
                    type="submit">
                    Prisijungti <span aria-hidden="true" class="text-2xl leading-none">→</span>
                </button>

                <p class="mt-10 text-center text-base text-slate-400 sm:mt-12 sm:text-lg">
                    Neturite paskyros?
                    <a href="register-form.php"
                        class="ml-1 font-semibold text-teal-300 transition-colors hover:text-teal-200 hover:underline focus-visible:rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-teal-300">
                        Sukurti paskyrą
                    </a>
                </p>
            </form>
        </section>
    </main>
</body>

</html>
