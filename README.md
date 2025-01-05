![logo_white](https://github.com/user-attachments/assets/20d2e574-c88c-4934-8e53-7edcfdf21060)

TradeWise is an open source trading journal platform that helps users document, track, and analyze their trades, offering insights into trading performance, profit and loss (PnL) analysis, and more to support informed decision-making.

# Key Features

<details>
  <summary>Dashboard Overview</summary>
  
  - A centralized location displaying key performance metrics and a high-level summary of trading activity.
  
  ![Dashboard-TRADE-WISE](https://github.com/user-attachments/assets/91afd8c8-3620-476b-b33a-6e2805e595b1)
</details>

<details>
  <summary>Comprehensive Trade Records</summary>
  
  - Document details such as currency pair, buy/sell values, prices, and positions.
  - Provides real-time profit and loss calculations with detailed percentage breakdowns.
  
  ![Jan-04-2025-TRADE-WISE](https://github.com/user-attachments/assets/8c47e12f-fd59-4b03-91bb-92b9750d232f)
</details>

<details>
  <summary>Trade Notes</summary>
  
  - Add detailed notes for each trade, including images and annotations for trade strategy analysis.
  
  ![Jan-04-2025-TRADE-WISE (1)](https://github.com/user-attachments/assets/27334c2f-e753-4074-8a8f-be21c0c7144d)
</details>

<details>
  <summary>Trade Limits</summary>
  
  - Set a maximum trade limit for each day to enforce disciplined trading and avoid over-trading.
  
  ![Limits-TRADE-WISE](https://github.com/user-attachments/assets/b257911c-8985-4689-bf75-07848247ecc2)
</details>

<details>
  <summary>Interactive Calendar</summary>
  
  - Visualize daily profits and losses in a calendar view.
  - Limited trading days are indicated to help traders manage and review their activity effectively.
  
  ![Calander-TRADE-WISE](https://github.com/user-attachments/assets/51b62eb7-0622-4657-a62c-bfef3290fe4a)
</details>

<details>
  <summary>Reports</summary>
  
  - Provides Daily, Monthly, and Yearly Reports to analyze trading performance across different timeframes.
  - Supports exporting reports to Excel for detailed analysis and record-keeping.
  
  ![Monthly-PnL-Report-TRADE-WISE](https://github.com/user-attachments/assets/84b61fba-fdea-4113-b30a-9bc2d4a38923)
</details>

<details>
  <summary>Multi-Tenancy</summary>
  
  - Fully supports multi-tenancy, allowing multiple users to operate within the same platform while maintaining data isolation and security.
  - Each tenant has a separate database or schema, ensuring data integrity and performance.

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
</details>

# Tech Stack

<div style="display: flex; align-items: center; gap: 20px;">
  <img src="https://github.com/vuejs/art/blob/master/logo.png" alt="Vue.js" width="50" />
  <img src="https://github.com/laravel/art/blob/master/laravel-logo.png" alt="Laravel" width="50" />
  <img src="https://raw.githubusercontent.com/marwin1991/profile-technology-icons/refs/heads/main/icons/mysql.png" alt="Laravel" width="50" />
</div>



