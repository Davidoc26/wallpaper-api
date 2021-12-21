<?php

namespace App\Console\Commands;

use App\Dto\RegisteredUserDto;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Contracts\Validation\Factory;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new user';

    private UserService $userService;
    private Factory $validator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserService $userService, Factory $validator)
    {
        parent::__construct();

        $this->userService = $userService;
        $this->validator = $validator;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $name = $this->ask('New user name');
        $email = $this->ask('New user email');
        $password = $this->ask('New user password');
        if ($this->validateInput($name, $email, $password)) {
            return Command::FAILURE;
        }

        $dto = new RegisteredUserDto($name, $email, $password);
        $user = $this->userService->register($dto);

        $this->info('Successfully created!');
        $this->table(['ID', 'Name', 'Email'], [
            [$user->id, $user->name, $user->email],
        ]);

        return Command::SUCCESS;
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return bool
     */
    private function validateInput(string $name, string $email, string $password): bool
    {
        $validator = $this->validator->make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required'],
            'email' => ['required', 'unique:users'],
            'password' => ['required'],
        ]);
        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return true;
        }

        return false;
    }
}
