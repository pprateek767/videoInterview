Features

Authentication (Laravel Breeze) with role-based access: admin, reviewer, candidate

Admin/Reviewer:

Create and manage interviews

Add questions

Review candidate submissions (with score + comment)

Candidate:

Record answers to interview questions via webcam/mic

Preview and submit answers

Can only submit once per question

Reviewer:

View candidate submissions

Leave scores and feedback

🛠️ Tech Stack

Backend: Laravel 10 (PHP 8.1+)

Frontend: Blade + TailwindCSS + Alpine.js

Database: MySQL

Video Storage: Local (Laravel Storage, public/)

Auth: Laravel Breeze

📦 Setup Instructions

1. Clone Repo
   git clone https://github.com/your-username/video-interview.git
   cd video-interview

2. Install Dependencies
   composer install
   npm install && npm run build

3. Environment Setup

Copy .env.example to .env:

cp .env.example .env

Update your .env file:

APP_NAME="VideoInterview"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=videointerview
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public

4. Generate App Key
   php artisan key:generate

5. Run Migrations
   php artisan migrate

(Optional) Seed an admin user:

php artisan db:seed --class=AdminSeeder

6. Link Storage
   php artisan storage:link

7. Start Server
   php artisan serve

Visit http://127.0.0.1:8000
🎉

👤 Test Accounts
Role Email Password
Admin admin@test.com
password
Reviewer reviewer@test.com
password
Candidate candidate@test.com
password
📹 Recording Flow

Candidate selects an interview.

Click Start → recording begins.

Click Stop → preview appears.

Candidate can Discard or Submit Answer.

Once submitted, answer is locked for that question.

⚠️ Known Limitations

Video is stored locally (storage/app/public) — not integrated with cloud storage yet.

No email notifications yet.

Only tested in Chrome/Edge (WebRTC API support required).

📄 License

MIT License.
