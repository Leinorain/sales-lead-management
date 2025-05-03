<?php

    namespace App\Infrastructure\Auth;

    use App\Domain\Auth\SessionInterface;

    class NativeSession implements SessionInterface
    {
        public function __construct()
        {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }

        public function set(string $key, mixed $value): void
        {
            $_SESSION[$key] = $value;
        }

        public function get(string $key): mixed
        {
            return $_SESSION[$key] ?? null;
        }

        public function has(string $key): bool
        {
            return isset($_SESSION[$key]);
        }

        public function remove(string $key): void
        {
            unset($_SESSION[$key]);
        }

        public function destroy(): void
        {
            session_destroy();
        }
    }