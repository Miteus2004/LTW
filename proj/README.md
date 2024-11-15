# SecondWave

## Group ltw05g04

- Xavier Martins (up202206632) 33.3%
- Eduado Portugal (up202206628) 33.3%
- Miguel Mateus (up202206944) 33.3%

## Install Instructions

    git clone <your_repo_url>
    git checkout final-delivery-v1
    cd .\database\
    sqlite database/database.db < database/script.sql
    cd ..
    php -S localhost:9000


## Screenshots
![mainpage](https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw05g04/assets/131909577/6176e880-74fd-4aaa-81df-1b21d5793357)

![profile](https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw05g04/assets/131909577/bbf3573e-a7a3-4199-95f0-79a580940789)

![cart](https://github.com/FEUP-LTW-2024/ltw-project-2024-ltw05g04/assets/131909577/9e907242-81c3-42d7-9b56-b74a6c962546)

## Implemented Features

**General**:

- [X] Register a new account.
- [X] Log in and out.
- [X] Edit their profile, including their name, username, password, and email.

**Sellers**  should be able to:

- [X] List new items, providing details such as category, brand, model, size, and condition, along with images.
- [X] Track and manage their listed items.
- [X] Respond to inquiries from buyers regarding their items and add further information if needed.
- [X] Print shipping forms for items that have been sold.

**Buyers**  should be able to:

- [X] Browse items using filters like category, price, and condition.
- [X] Engage with sellers to ask questions or negotiate prices.
- [X] Add items to a wishlist or shopping cart.
- [X] Proceed to checkout with their shopping cart (simulate payment process).

**Admins**  should be able to:

- [X] Elevate a user to admin status.
- [X] Introduce new item categories, sizes, conditions, and other pertinent entities.
- [X] Oversee and ensure the smooth operation of the entire system.

**Security**:
We have been careful with the following security aspects:

- [X] **SQL injection**
- [X] **Cross-Site Scripting (XSS)**
- [ ] **Cross-Site Request Forgery (CSRF)**

**Password Storage Mechanism**: hash_password&verify_password

**Aditional Requirements**:

We also implemented the following additional requirements (you can add more):

- [ ] **Rating and Review System**
- [ ] **Promotional Features**
- [ ] **Analytics Dashboard**
- [ ] **Multi-Currency Support**
- [ ] **Item Swapping**
- [ ] **API Integration**
- [ ] **Dynamic Promotions**
- [ ] **User Preferences**
- [ ] **Shipping Costs**
- [X] **Real-Time Messaging System**
