🚀 EduAssignModern Laravel-Powered Assignment Management System🌟 Project OverviewEduAssign is a streamlined academic management platform built to bridge the gap between educators and students. It digitizes the assignment lifecycle—from creation and distribution to submission and real-time tracking—within a secure, role-based environment.Built with Laravel, this system emphasizes clean architecture, secure data handling, and a high-performance user experience suitable for modern educational institutions.📂 System Architecture & Directory MapComponentPathResponsibilityLogic (Controllers)app/Http/Controllers/Directing traffic and processing business logicModels (ELOQUENT)app/Models/Database schema mapping and relationshipsViews (Blade)resources/views/Responsive UI components and dashboardsRoutesroutes/web.phpURL definitions and Middleware assignmentMigrationsdatabase/migrations/Version-controlled database schema🛠️ PrerequisitesEnsure your development environment meets these requirements:PHP: >= 8.2Composer: Dependency Manager for PHPNode.js & NPM: For frontend asset compilationMySQL: Database server🚀 Professional Installation Guide1. Repository SetupClone the project and enter the directory:Bashgit clone https://github.com/ismail-dz-16/EduAssign.git
cd EduAssign
2. Dependency ManagementInstall both the backend engine and frontend styling assets:Bash# Install PHP logic
composer install

# Install UI dependencies & compile assets
npm install
npm run build
3. Environment ConfigurationInitialize your environment variables and link the application security key:Bashcp .env.example .env
php artisan key:generate
[!IMPORTANT]Open the .env file and configure your local database connection:Extrait de codeDB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eduassign_db
DB_USERNAME=root
DB_PASSWORD=your_secure_password
4. Database Schema DeploymentRun the migrations to build the tables (Assignments, Users, Submissions):Bashphp artisan migrate
📖 User Role BreakdownFeature👨‍🏫 Teacher Access🎓 Student AccessCreate Assignments✅❌Set Deadlines✅❌Real-time Submission Tracking✅❌View Available Tasks✅✅Upload Work/Files❌✅Track Progress Dashboard✅✅🖥️ Local DeploymentOnce configured, launch the development server:Bashphp artisan serve
The application will be live at: http://127.0.0.1:8000🛡️ Security & Best PracticesMiddleware Protection: Routes are protected by Laravel's auth middleware, ensuring only registered users access the dashboards.Mass Assignment Protection: Eloquent models use $fillable to prevent malicious data injection.Environment Security: Sensitive data (API keys, passwords) is strictly kept in .env and excluded from Git for safety.⚖️ License & PurposeThis project was developed by Ismail Benmbarek as a demonstration of professional web engineering. It is open-source and intended for educational use.💡 Pro Tip: To preview this Markdown with full formatting in VS Code, press Ctrl + Shift + V.
