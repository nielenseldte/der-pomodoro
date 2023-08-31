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

## Test Cases and Test Data

Let's Test `der-pomodoro` by creating a user and performing some tasks!

1. Click on the register button and enter the following:
    1. Name: `Jane Doe`
    2. Email `Jane@Doe.com`
    3. Password `Jane12345`
    4. confirm password `Jane12345`
2. Now you should see the dashboard infront of you with the timer on the left, and inspirational quote, followed by a daily goal bar and a productivity score.
3. Click in the top right on your name (Jane Doe) in this case, and in the dropdown click on `My Settings`.
4. Now you will see the settings screen titled 'Pomodoro Settings'
5. In the Settings form change your focus length to any outrageous number, for example 200, or 1 minutes.
6. You will get an error message telling you your focus session can only be between 15 and 50 minutes
7. Feel free to test out the other fields too for their validations.
8. Set your focus length to 15 minutes, your short break length to 3 minutes, your long break length to 10 minutes, your long break interval to 3 focus sessions and your daily goal to 1 hour. These are the minumum allowed values for each setting.
9. You will get a confirmation message on screen below the 'Pomodoro Settings' header telling you that your settings have been successfully updated
10. Click on Timer in the top header and now the timer should display your new session length! 15 minutes.
11. Click start and watch it tik, in the meantime, grab a coffee or focus :)
12. Feel free to click the 'Stop' button to test the pause and play fucntionality.
13. Once your timer is ticked to 0:00 the break will display! your short break length, which in this case is 3 minutes.
14. Play the break and let it tick
15. Once you have now completed, go to the 'Stats' page in the header and look at the stats to match the following:
    1. The Sessions started should be 2, since once a break finishes a session is automatically queued.
    2. The sessions finished should be 1
    3. The hours focused should be
    4. Your productivity Score should be 100 percent since you haven't cancelled any focus sessions and completed the one you started.
    5. The chart should also display a bar indicating your time focused.
16. Return to the Dashboard page and see your productivity score and goal progress also updated.
17. Now Execute step 10 and 11 again to start another focus session, but this time click the 'Cancel' button.
18. Now you will be taken back to the beginning of your next focus session having cancelled that one
19. Go to the Stats page and see that your productivity score is no longer 100% since you have now cancelled a session
20. Test Case complete, but feel free to play around!

## License

`der-pomodoro` is licensed under the MIT License.
