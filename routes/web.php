<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\AddCustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Models\ProductModel;

Route::get('welcome', function () {
    return view('welcome');
})->name('welcome'); 

Route::get('/', function () {
    return view('layout.home');
})->name('index');

Route::get('/home', function () {
    return view('layout.home');
})->name('home');

Route::get('/about-us', function () {
    return view('layout.about_us');
})->name('about_us');

Route::get('/services', function () {
    return view('layout.services');
})->name('services');

Route::get('/footer', function () {
    return view('layout.footer');
})->name('footer');

Route::get('/login', function () {
    return view('login&signup.login');
})->name('login');

Route::post('/login', [UserRegistrationController::class, 'user_login'])->name('user_login');
Route::get('/forget_password',[UserRegistrationController::class, 'forget_password_form'])->name('forget_password_form');
Route::post('/send_code', [UserRegistrationController::class, 'send_code'])->name('send_code');
Route::get('/verify_otp', [UserRegistrationController::class, 'verify_otp_form'])->name('verify_otp_form');
Route::post('/forgot-password/verify', [UserRegistrationController::class, 'verify_code'])->name('verify_code');
Route::get('/verify_email', [UserRegistrationController::class, 'email_verification_form'])->name('email_verification_form');
Route::post('/verify_email_code', [UserRegistrationController::class, 'verify_email_code'])->name('verify_email_code');
// Route::get('/signup', function () {
//     return view('login&signup.signup');
// })->name('signup');

Route::get('/signup',[UserRegistrationController::class, 'user_signup_form'])->name('signup');
Route::post('/user_signup/save', [UserRegistrationController::class, 'user_signup'])->name('user_signup');
Route::post('/change_password', [UserRegistrationController::class, 'update_user_password'])->name('update_user_password');

Route::get('/contact_us', function(){
    return view('layout.contact_us');
})->name('contact_us');

//user section
Route::middleware(['auth:cuser'])->group(function () {
    Route::get('/user_dashboard', [UserDashboardController::class, 'user_dashboard'])->name('user_dashboard');

    //user logout
    Route::post('/user_logout', [UserRegistrationController::class, 'user_logout'])->name('user_logout');

    //user profile view
    Route::get('/user_profile',[UserRegistrationController::class, 'user_profile'])->name('user_profile');

    //update user profile
    Route::post('/update_user_profile', [UserRegistrationController::class, 'update_user_profile'])->name('update_user_profile');

    //customer management add customer
    Route::get('/add_customer', [AddCustomerController::class, 'add_customer_form'])->name('add_customer');
    Route::post('/add_customer', [AddCustomerController::class, 'save_customer'])->name('save_customer');

    //user index page
    Route::get('/user_index', function(){
        return view('user_layout.user_index');
    })->name('user_index');

    //manage customers
    Route::get('/manage_customers', function(){
        $customers = auth('cuser')->user()->customers; // Fetch customers for the logged-in user
        return view('user_layout.user_customer.manage_customers', compact('customers'));
    })->name('manage_customers');

    //edit, update, delete customer
    Route::get('/edit_customer/{id}', [AddCustomerController::class, 'edit_customer'])->name('edit_customer');
    Route::put('/update_customer/{id}', [AddCustomerController::class, 'update_customer'])->name('update_customer');
    Route::get('/delete_customer/{id}', [AddCustomerController::class, 'delete_customer'])->name('delete_customer');
Route::post('/forgot-password/verify', [UserRegistrationController::class, 'verify_code2'])->name('verify_code2');
    
});

//admin section
Route::get('/admin_index', function(){
    return view('admin_layout.admin_index');
})->name('admin_index');

Route::get('/create-admin', [AdminController::class, 'admin_create'])->name('admin_create');        
// Route::get('/create-admin', [AdminController::class, 'create_admin_form'])->name('admin_create');
Route::post('/create-admin', [AdminController::class, 'store_admin'])->name('admin_store');

Route::get('admin/login', function() {
    return view('admin_layout.admin_login');
})->name('admin.login.form');

Route::post('admin_login', [AdminController::class, 'admin_login'])->name('admin_login');

Route::middleware(['auth:cadmin'])->group(function () {
    //admin logout
    Route::get('/admin_dashboard', [AdminController::class, 'admin_dashboard'])->name('admin_dashboard');
    Route::get('/admin_logout', [AdminController::class, 'admin_logout'])->name('admin_logout');
    Route::get('/manage_users', [AdminController::class, 'manage_users'])->name('manage_users');
    Route::get('/admin_edit_user/{id}', [AdminController::class, 'edit_user'])->name('admin_edit_user');
    Route::post('/admin_update_user/{id}', [AdminController::class, 'admin_update_user'])->name('admin_update_user');
    Route::get('/admin_delete_user/{id}', [AdminController::class, 'delete_user'])->name('admin_delete_user');
    Route::get('/admin_add_user', [AdminController::class, 'add_user'])->name('admin_add_user'); // Route to display the add user form
    Route::post('/admin_store_user', [AdminController::class, 'admin_store_user'])->name('admin_store_user'); // Route to handle form submission
});

Route::middleware(['auth:cuser'])->group(function () {
    Route::get('/user_shop_profile',[ShopController::class, 'user_shop_profile'])->name('user_shop_profile');
    Route::get('/add_user_shop_profile',[ShopController::class, 'add_user_shop_profile'])->name('add_user_shop_profile');
    Route::post('/store_user_shop_profile',[ShopController::class, 'store_user_shop_profile'])->name('store_user_shop_profile');
    Route::get('/edit_user_shop_profile/{id}', [ShopController::class, 'edit_user_shop_profile'])->name('edit_user_shop_profile');
    Route::post('/update_user_shop_profile/{id}', [ShopController::class, 'update_user_shop_profile'])->name('update_user_shop_profile');
});

Route::middleware(['auth:cuser'])->group(function () {
    Route::get('/invoice', [InvoiceController::class, 'make_new_invoice'])->name('make_new_invoice');
    Route::post('/store_invoice', [InvoiceController::class, 'store_invoice'])->name('store_invoice');
    Route::get('/show_invoice/{id}', [InvoiceController::class, 'show_invoice'])->name('show_invoice');
    Route::get('/invoices', [InvoiceController::class, 'list_invoices'])->name('list_invoices');
    Route::get('/draft_invoices', [InvoiceController::class, 'draft_invoice_list'])->name('draft_invoice_list');
    Route::get('/edit_invoice/{id}', [InvoiceController::class, 'edit_invoice'])->name('edit_invoice');
    Route::post('/submit_draft/{id}', [InvoiceController::class, 'submit_draft'])->name('submit_draft');
    Route::put('/update-invoice/{id}', [InvoiceController::class, 'update_invoice'])->name('update_invoice');
    Route::get('/get-products-by-shop/{shopId}', [InvoiceController::class, 'getProductsByShop'])->name('get_products_by_shop');
    Route::get('/invoice/{id}/download', [InvoiceController::class, 'downloadInvoice']) ->name('invoice_download');
    Route::get('/invoice/{id}/mail', [InvoiceController::class, 'invoice_mail']) ->name('invoice_mail');
    Route::get('/get-batches/{product}', [InvoiceController::class, 'products_batches'])->name('products_batches');

});

Route::middleware(['auth:cuser'])->group(function () {
    Route::get('/add_products',[ProductController::class,'add_products'])->name('add_products');
    Route::post('/store_products',[ProductController::class,'store_products'])->name('store_products');
    Route::get('/manage_products',[ProductController::class,'manage_products'])->name('manage_products');
    
    Route::get('/api/products/by-shop/{shop}', function($shopId) {
    return ProductModel::where('shop_id', $shopId)->get(['id', 'product_name', 'product_id', 'price']);
    });
    Route::get('/edit_products/{id}', [ProductController::class,'edit_products'])->name('edit_products');
    Route::put('/update_products/{id}', [ProductController::class,'update_products'])->name('update_products');
    Route::get('/delete_products/{id}', [ProductController::class,'delete_products'])->name('delete_products');
    Route::get('/stock_alert', [ProductController::class,'stock_alert'])->name('stock_alert');

});

Route::middleware(['auth:cuser'])->group(function () {
    Route::get('payment_history',[PaymentController::class,'paymentHistory'])->name('payment_history');
    Route::get('payments_dues',[PaymentController::class,'paymentsDues'])->name('payments_dues');
    Route::post('store_payment',[PaymentController::class,'storePayment'])->name('store_payment');
});
