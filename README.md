![logo_white](https://github.com/user-attachments/assets/20d2e574-c88c-4934-8e53-7edcfdf21060)

TradeWise is an open source spot trading journal platform that helps users document, track, and analyze their trades, offering insights into trading performance, profit and loss (PnL) analysis, and more to support informed decision-making.

# Key Features

<details>
  <summary>Dashboard Overview</summary>
  
  - A centralized location displaying key performance metrics and a high-level summary of trading activity.
  
  ![Dashboard-TRADE-WISE](https://github.com/user-attachments/assets/2957571a-df93-4f7a-a2a4-5716c7bfb522)
</details>

<details>
  <summary>Comprehensive Trade Records</summary>
  
  - Document details such as currency pair, buy/sell values, prices, and positions.
  - Provides real-time profit and loss calculations with detailed percentage breakdowns.
  
  ![Jan-04-2025-TRADE-WISE](https://github.com/user-attachments/assets/ed7e0342-4938-4dbf-9785-81b5f38f5e26)
</details>

<details>
  <summary>Trade Notes</summary>
  
  - Add detailed notes for each trade, including images and annotations for trade strategy analysis.
  
  ![Jan-04-2025-TRADE-WISE (1)](https://github.com/user-attachments/assets/f5314196-3ccb-4844-a7f2-9e17ca1bb8d9)
</details>

<details>
  <summary>Trade Limits</summary>
  
  - Set a maximum trade limit for each day to enforce disciplined trading and avoid over-trading.
  
  ![Limits-TRADE-WISE](https://github.com/user-attachments/assets/b59ef774-ae19-4334-832a-52125ce7b199)
</details>

<details>
  <summary>Interactive Calendar</summary>
  
  - Visualize daily profits and losses in a calendar view.
  - Limited trading days are indicated to help traders manage and review their activity effectively.
  
  ![Calander-TRADE-WISE](https://github.com/user-attachments/assets/f6c4770c-c9a2-47af-9578-6dfbe3b5b8a1)
</details>

<details>
  <summary>Reports</summary>
  
  - Provides Daily, Monthly, and Yearly Reports to analyze trading performance across different timeframes.
  - Supports exporting reports to Excel for detailed analysis and record-keeping.
  
  ![Monthly-PnL-Report-TRADE-WISE](https://github.com/user-attachments/assets/6a263742-9e81-4e7b-867e-81801519f569)
</details>

<details>
  <summary>Multi-Tenancy</summary>
  
  - Fully supports multi-tenancy, allowing multiple users to operate within the same platform while maintaining data isolation and security.
  - Each tenant has a separate database, ensuring data integrity and performance.

</details>

# Setup

<details>
  <summary>Click to expand the setup instructions</summary> <br>
    
Clone the repository <br>
```
git clone https://github.com/sameera-madushan/Trade-Wise.git
```

Change directories into web <br>
```
cd Trade-Wise/
```

Install composer <br>
```
composer install
```

Create the .env file by duplicating the .env.example file <br>
```
cp .env.example .env
```

Set the APP_KEY value <br>
```
php artisan key:generate
```

Clear your cache & config (OPTIONAL)
``` 
php artisan cache:clear && php artisan config:clear
```

Run migrations and seeds
``` 
php artisan migrate --seed
```

Install npm packages to build assets
```
npm install
```

Finally, run your project in the browser!
```
npm run dev
php artisan serve
```
## Running Migrations for All User Databases.
Before running any commands for individual users, you must first run the base migration to ensure the main application database is set up. This can be done using the following `php artisan migrate` command.

After that, you will need to run migrations for all user databases using `php artisan migrate:all-users` command.

This command loops through all registered users, switches to their respective database, and runs the migrations on them. It uses the `sqlite_user` database connection configured for each user in the system.

</details>

# Tech Stack

<div style="display: flex; align-items: center; gap: 20px;">
  <img src="https://github.com/vuejs/art/blob/master/logo.png" alt="Vue.js" width="50" />
  <img src="https://github.com/laravel/art/blob/master/laravel-logo.png" alt="Laravel" width="50" />
  <img src="https://avatars.githubusercontent.com/u/47703742?s=200&v=4" alt="Laravel" width="50" />
  <img src="https://raw.githubusercontent.com/marwin1991/profile-technology-icons/refs/heads/main/icons/mysql.png" alt="Laravel" width="50" />
</div>



