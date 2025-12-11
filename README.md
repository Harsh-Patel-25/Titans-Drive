# TITAN'S DRIVE - BMW Car Rental System

A complete full-stack web application for BMW car rental service built with HTML, CSS, JavaScript, PHP, and MySQL.

## 🚗 Features

### User Features
- **User Authentication**: Secure login and registration system
- **Car Browsing**: View all available BMW models with detailed specifications
- **Advanced Filtering**: Filter cars by series, transmission, fuel type, and price
- **Car Details**: Detailed view of each car with specifications and features
- **Booking System**: Easy booking process with date selection and price calculation
- **User Dashboard**: View all bookings and their status
- **Profile Management**: Update personal information and change password
- **Document Upload**: Upload driving license and ID proofs for verification
- **Booking Management**: View, track, and cancel bookings

### Admin Features
- **Admin Dashboard**: Overview of system statistics
- **Car Management**: Add, edit, and delete cars from inventory
- **Booking Management**: View, confirm, complete, or cancel bookings
- **User Management**: View and manage registered users
- **Document Verification**: Approve or reject uploaded documents
- **Real-time Statistics**: Track total users, cars, bookings, and revenue

## 📁 Project Structure

```
titans-drive/
│
├── admin/
│   ├── dashboard.php
│   ├── login.php
│   ├── manage_cars.php
│   ├── manage_bookings.php
│   ├── manage_users.php
│   ├── manage_documents.php
│   ├── verify_document.php
│   └── logout.php
│
├── api/
│   └── get_featured_cars.php
│
├── css/
│   └── style.css
│
├── js/
│   └── main.js
│
├── includes/
│   ├── navbar.php
│   └── footer.php
│
├── uploads/
│   ├── documents/
│   └── cars/
│
├── config.php
├── index.php
├── login.php
├── signup.php
├── cars.php
├── car_details.php
├── dashboard.php
├── profile.php
├── upload_documents.php
├── booking_details.php
├── about.php
├── contact.php
└── logout.php
```

## 🛠️ Installation Instructions

### Prerequisites
- XAMPP/WAMP/LAMP (Apache + PHP + MySQL)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web browser

### Step 1: Setup Web Server
1. Install XAMPP from https://www.apachefriends.org/
2. Start Apache and MySQL services

### Step 2: Database Setup
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database named `titans_drive`
3. Import the provided SQL schema or run the SQL commands from the database schema file
4. The default admin credentials are:
   - Username: `admin`
   - Password: `password` (Please change after first login)

### Step 3: Project Setup
1. Extract the project files
2. Copy the `titans-drive` folder to your web server root directory:
   - For XAMPP: `C:/xampp/htdocs/`
   - For WAMP: `C:/wamp64/www/`
   - For LAMP: `/var/www/html/`

### Step 4: Configuration
1. Open `config.php` in a text editor
2. Update database credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'titans_drive');
   ```
3. Update site URL:
   ```php
   define('SITE_URL', 'http://localhost/titans-drive');
   ```

### Step 5: File Permissions
Ensure the `uploads/` directory has write permissions:
```bash
chmod -R 755 uploads/
```

### Step 6: Access the Application
1. **User Interface**: http://localhost/titans-drive/
2. **Admin Panel**: http://localhost/titans-drive/admin/
   - Username: admin
   - Password: password

## 📋 Default Credentials

### Admin Access
- **Username**: admin
- **Email**: admin@titansdrive.com
- **Password**: password

### Test User Account
Create your own user account through the signup page or use phpMyAdmin to create test users.

## 🎨 Features Breakdown

### Frontend Technologies
- **HTML5**: Semantic markup and structure
- **CSS3**: Modern styling with flexbox and grid
- **JavaScript**: Interactive features and AJAX calls
- **Responsive Design**: Mobile-first approach

### Backend Technologies
- **PHP 7.4+**: Server-side logic and processing
- **PDO**: Secure database interactions
- **Session Management**: User authentication and authorization
- **File Upload Handling**: Document management

### Database Design
- **Users Table**: Store user information
- **Cars Table**: BMW vehicle inventory
- **Bookings Table**: Rental bookings with dates and pricing
- **Documents Table**: User document uploads for verification
- **Admins Table**: Admin user management
- **Reviews Table**: Customer feedback system

## 🔐 Security Features

- Password hashing using PHP's `password_hash()`
- SQL injection prevention using PDO prepared statements
- XSS protection with input sanitization
- Session-based authentication
- File upload validation
- CSRF protection ready

## 💡 Usage Guide

### For Users

1. **Registration**
   - Click "Sign Up" and fill in your details
   - Minimum password length: 6 characters
   - Email must be unique

2. **Browsing Cars**
   - View all available BMW models
   - Use filters to narrow down choices
   - Click "View Details" for complete specifications

3. **Making a Booking**
   - Select pickup and return dates
   - Choose pickup location
   - Add special requests if needed
   - Submit booking request

4. **Upload Documents**
   - Navigate to "Upload Documents"
   - Upload driving license (mandatory)
   - Upload ID and address proof
   - Wait for admin verification

5. **Manage Bookings**
   - View all bookings in dashboard
   - Check booking status
   - Cancel pending/confirmed bookings

### For Admins

1. **Login to Admin Panel**
   - Navigate to `/admin/`
   - Use admin credentials

2. **Manage Cars**
   - Add new cars with specifications
   - Edit existing car details
   - Delete cars from inventory
   - Update availability status

3. **Manage Bookings**
   - View all booking requests
   - Confirm pending bookings
   - Mark bookings as completed
   - Cancel bookings if needed

4. **Verify Documents**
   - Review uploaded documents
   - Approve verified documents
   - Reject with reasons if invalid

5. **Monitor Dashboard**
   - Track system statistics
   - View recent activities
   - Monitor revenue

## 📊 Database Schema

### Users Table
```sql
- user_id (Primary Key)
- full_name
- email (Unique)
- password (Hashed)
- phone
- address, city, state, zip_code
- license_number
- is_active
- created_at, updated_at
```

### Cars Table
```sql
- car_id (Primary Key)
- model_name, series
- year, color
- transmission, fuel_type, seats
- price_per_day
- mileage, engine
- description, features
- availability_status
- created_at, updated_at
```

### Bookings Table
```sql
- booking_id (Primary Key)
- user_id (Foreign Key)
- car_id (Foreign Key)
- pickup_date, return_date
- pickup_location
- total_days, total_price
- booking_status (pending/confirmed/completed/cancelled)
- payment_status (pending/paid/refunded)
- special_requests
- created_at, updated_at
```

### Documents Table
```sql
- document_id (Primary Key)
- user_id (Foreign Key)
- document_type (license/id_proof/address_proof)
- document_url
- verification_status (pending/verified/rejected)
- uploaded_at, verified_at
- rejection_reason
```

## 🚀 Customization

### Adding New Car Models
1. Login to admin panel
2. Navigate to "Manage Cars"
3. Fill in all car details
4. Set price per day
5. Add features as comma-separated values

### Modifying Pickup Locations
Edit `car_details.php` and update the dropdown options:
```php
<option value="Your Location">Your Location</option>
```

### Changing Color Scheme
Edit `css/style.css` and modify CSS variables:
```css
:root {
    --primary-color: #1c4587;
    --secondary-color: #0066cc;
    --bmw-blue: #0066b1;
}
```

### Adding Email Notifications
Integrate PHPMailer or use PHP's `mail()` function in:
- `signup.php` - Welcome email
- `car_details.php` - Booking confirmation
- `admin/manage_bookings.php` - Status updates

## 🔧 Troubleshooting

### Common Issues

**Database Connection Error**
- Check MySQL service is running
- Verify credentials in `config.php`
- Ensure database `titans_drive` exists

**Upload Directory Error**
- Check folder permissions (755 or 777)
- Verify `uploads/` directory exists
- Check PHP upload settings in `php.ini`

**Session Issues**
- Clear browser cookies
- Check session settings in `php.ini`
- Ensure proper session start in `config.php`

**Blank Pages**
- Enable error reporting in PHP
- Check Apache error logs
- Verify all required files exist

## 📝 Future Enhancements

- [ ] Payment gateway integration (Razorpay/Stripe)
- [ ] Email notifications system
- [ ] SMS alerts for bookings
- [ ] Advanced search with multiple filters
- [ ] Customer reviews and ratings
- [ ] Loyalty program
- [ ] Invoice generation PDF
- [ ] Real-time availability calendar
- [ ] Google Maps integration for locations
- [ ] Multi-language support

## 🤝 Support

For issues or questions:
- Check the troubleshooting section
- Review PHP and MySQL error logs
- Verify all installation steps completed

## 📄 License

This project is created for educational purposes.

## 👨‍💻 Developer Notes

### Best Practices Implemented
- MVC-like structure separation
- Prepared statements for SQL
- Input validation and sanitization
- Responsive mobile-first design
- Clean and readable code
- Proper error handling
- Session security

### Technologies Used
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP 7.4+, PDO
- **Database**: MySQL 5.7+
- **Server**: Apache 2.4+

## 🎯 Project Highlights

✅ Complete authentication system  
✅ Role-based access control (User/Admin)  
✅ CRUD operations for all entities  
✅ File upload and management  
✅ Responsive design  
✅ Search and filter functionality  
✅ Dynamic pricing calculation  
✅ Status tracking system  
✅ Document verification workflow  
✅ Professional UI/UX design  

---

**TITAN'S DRIVE** - Where Luxury Meets Performance 🏎️

*Built with ❤️ for BMW enthusiasts*