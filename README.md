<h1 align="center">EduAssign</h1>

<p align="center">
    A modern web-based assignment management system built with Laravel.
</p>

<hr>

<h2>Overview</h2>

<p>
    <strong>EduAssign</strong> is a web-based assignment management platform designed to simplify
    academic workflows. It enables teachers to create and manage assignments while allowing
    students to submit their work and track progress through a clean, intuitive interface.
</p>

<p>
    The project focuses on <strong>simplicity</strong>, <strong>clarity</strong>, and
    <strong>efficient task management</strong> in educational environments.
</p>

<hr>

<h2>Project Information</h2>

<ul>
    <li><strong>Project Name:</strong> EduAssign</li>
    <li><strong>Repository:</strong> EduAssign</li>
    <li><strong>GitHub Username:</strong> ismail-dz-16</li>
    <li><strong>Developed by:</strong> Ismail Benmbarek</li>
</ul>

<hr>

<h2>Features</h2>

<h3>Teacher Features</h3>
<ul>
    <li>Create and manage assignments</li>
    <li>Define multiple question types</li>
    <li>Track student submissions in real time</li>
    <li>Access a dedicated teacher dashboard</li>
</ul>

<h3>Student Features</h3>
<ul>
    <li>View available assignments</li>
    <li>Submit assignments online</li>
    <li>Track submission status and deadlines</li>
    <li>Access a student-specific dashboard</li>
</ul>

<h3>General Features</h3>
<ul>
    <li>Role-based authentication (Teacher / Student)</li>
    <li>Secure login and access control</li>
    <li>Responsive design for all devices</li>
    <li>Clean and modern user interface</li>
</ul>

<hr>

<h2>Technology Stack</h2>

<ul>
    <li><strong>Backend:</strong> Laravel</li>
    <li><strong>Frontend:</strong> Blade, HTML, CSS, JavaScript</li>
    <li><strong>Database:</strong> MySQL</li>
    <li><strong>Authentication:</strong> Laravel built-in authentication</li>
    <li><strong>Environment:</strong> PHP, Composer, Node.js</li>
</ul>

<hr>

<h2>How the Application Works</h2>

<ol>
    <li>Users register and authenticate.</li>
    <li>Each user is redirected based on their role (teacher or student).</li>
    <li>Teachers create and manage assignments.</li>
    <li>Students view assignments and submit their work.</li>
    <li>Teachers monitor submissions and student progress.</li>
</ol>

<hr>

<h2>Installation and Setup</h2>

<p>Follow the steps below to run the project locally on Ubuntu or any Linux system.</p>

<h3>1. Clone the Repository</h3>

<pre><code>git clone https://github.com/ismail-dz-16/EduAssign.git
cd EduAssign
</code></pre>

<h3>2. Install PHP Dependencies</h3>

<pre><code>composer install
</code></pre>

<h3>3. Install Frontend Dependencies</h3>

<pre><code>npm install
npm run build
</code></pre>

<h3>4. Environment Configuration</h3>

<p>Create the environment file:</p>

<pre><code>cp .env.example .env
</code></pre>

<p>Generate the application key:</p>

<pre><code>php artisan key:generate
</code></pre>

<p>Configure your database in <code>.env</code>:</p>

<pre><code>DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
</code></pre>

<h3>5. Run Database Migrations</h3>

<pre><code>php artisan migrate
</code></pre>

<h3>6. Start the Development Server</h3>

<pre><code>php artisan serve
</code></pre>

<p>Access the application at:</p>

<pre><code>http://127.0.0.1:8000
</code></pre>

<hr>

<h2>Git and Version Control Notes</h2>

<ul>
    <li>The <code>.env</code> file is excluded for security reasons.</li>
    <li>The <code>vendor</code> directory is not included in the repository.</li>
    <li>Run <code>composer install</code> after cloning to install dependencies.</li>
</ul>

<p>
    These practices follow Laravel and GitHub security standards.
</p>

<hr>

<h2>Project Purpose</h2>

<p>
    EduAssign was developed to digitize assignment workflows, reduce manual handling,
    and provide a clear separation between teacher and student responsibilities
    within an educational environment.
</p>

<hr>

<h2>License</h2>

<p>
    This project is open-source and available for educational and learning purposes.
    You may modify and use it according to your needs.
</p>
