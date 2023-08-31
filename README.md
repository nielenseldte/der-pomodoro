<p align="center"><a href="https://der-pomodoro.work" target="_blank"><img src="https://github.com/otrsw/der-pomodoro-main/blob/master/public/logo/logo.png" width="400" alt="Der Pomodoro"></a></p>


# der-pomodoro

`der-pomodoro` is a simple Pomodoro timer application written in PHP that helps you stay focused and productive.

## Prerequisites

Before installing `der-pomodoro`, make sure you have the following software installed on your machine:

- PHP
- Composer

## Installation

To install `der-pomodoro` locally, follow these steps:

1. Clone the repository: `git clone https://github.com/nielenseldte/der-pomodoro.git`
2. Navigate to the cloned repository: `cd der-pomodoro`
3. Install the dependencies: `composer install`
4. Set up the database:
    1. Copy the `.env.example` file and save it as `.env` in the root directory of the project.
    2. Open the `.env` file and change the `DB_CONNECTION` value to `sqlite` if you do not have MySQL installed. If you are using MySQL, make sure to provide the correct database credentials in the `.env` file.
    3. Run the database migrations: `php artisan migrate:fresh`
5. Start the application: `php -S localhost:8000`

After completing these steps, the application should be running locally on your machine at `http://localhost:8000`.

## Usage

To use `der-pomodoro`, simply open `http://localhost:8000` in your web browser and follow the on-screen instructions to set up your Pomodoro timer. You can customize the length of your work and break sessions, as well as the number of sessions you want to complete.

Once you start the timer, the application will keep track of your progress and notify you when it's time to take a break or start a new work session.

## Contributing

Contributions to `der-pomodoro` are welcome! If you have an idea for a new feature or want to report a bug, please open an issue on the repository's issue tracker.

If you want to contribute code, please fork the repository and submit a pull request with your changes.

##Test Cases and Test Data

here

## License

`der-pomodoro` is licensed under the MIT License.
