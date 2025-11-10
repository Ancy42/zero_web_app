// resources/js/router/index.js
import { createRouter, createWebHistory } from "vue-router";

// import master store
import { useMaster } from "../stores/MasterStore";

// import layouts
import defaultLayout from "../layouts/default.vue";
import authLayout from "../layouts/auth.vue";
import layoutBlank from "../layouts/blank.vue";
import blogLayout from "../layouts/blog.vue";

// import pages
const Home = () => import("../pages/Home.vue");
const Shop = () => import("../pages/Shop.vue");
const ShopDetails = () => import("../pages/ShopDetails.vue");
const ProductDetails = () => import("../pages/ProductDetails.vue");
const CategoryProduct = () => import("../pages/CategoryProduct.vue");
const Checkout = () => import("../pages/Checkout.vue");

const Dashboard = () => import("../pages/Dashboard.vue");
const OrderHistory = () => import("../pages/OrderHistory.vue");
const OrderDetails = () => import("../pages/OrderDetails.vue");
const Wishlist = () => import("../pages/Wishlist.vue");
const MyProfile = () => import("../pages/MyProfile.vue");
const ManageAddress = () => import("../pages/ManageAddress.vue");
const Support = () => import("../pages/Support.vue");
const TermsAndConditions = () => import("../pages/TermsAndConditions.vue");
const PrivacyPolicy = () => import("../pages/PrivacyPolicy.vue");
const AddNewAddress = () => import("../pages/AddNewAddress.vue");
const EditAddress = () => import("../pages/EditAddress.vue");
const AboutUs = () => import("../pages/AboutUs.vue");
const ChangePassword = () => import("../pages/ChangePassword.vue");
const BuyNow = () => import("../pages/BuyNow.vue");
const MostPopular = () => import("../pages/MostPopular.vue");
const ContactUs = () => import("../pages/ContactUs.vue");
const BestDeal = () => import("../pages/BestDeal.vue");
const Products = () => import("../pages/Products.vue");
const Category = () => import("../pages/Category.vue");
const SupportTicket = () => import("../pages/SupportTicket.vue");
const SupportTicketDetails = () => import("../pages/SupportTicketDetails.vue");
const FlashSale = () => import("../pages/FlashSale.vue");
const Blog = () => import("../pages/Blog.vue");
const BlogDetails = () => import("../pages/BlogDetails.vue");
const PolicyPages = () => import("../pages/PolicyPages.vue");
const Massages = () => import("../pages/Messages.vue");

// 404 page
const NotFound = () => import("../errors/404.vue");

// all pages router will be here
const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
        meta: {
            layout: defaultLayout,
            title: "Home",
        },
    },
    {
        path: "/shops",
        name: "shop",
        component: Shop,
        meta: {
            layout: defaultLayout,
            title: "Shops",
        },
    },
    {
        path: "/products",
        name: "products",
        component: Products,
        meta: {
            layout: defaultLayout,
            title: "Products",
        },
    },
    {
        path: "/categories",
        name: "categories",
        component: Category,
        meta: {
            layout: defaultLayout,
            title: "Categories",
        },
    },
    {
        path: "/most-popular",
        name: "most-popular",
        component: MostPopular,
        meta: {
            layout: defaultLayout,
            title: "Most Popular Products",
        },
    },
    {
        path: "/best-deal",
        name: "best-deal",
        component: BestDeal,
        meta: {
            layout: defaultLayout,
            title: "Best Deal Products",
        },
    },
    {
        path: "/flash-sale/:id",
        name: "flash-sale",
        component: FlashSale,
        meta: {
            layout: defaultLayout,
            title: "Flash Sale Products",
        },
    },
    {
        path: "/shops/:id",
        name: "shop-detail",
        component: ShopDetails,
        meta: {
            layout: defaultLayout,
            title: "Shop Details",
        },
    },
    {
        path: "/products/:slug",
        name: "productDetails",
        component: ProductDetails,
        meta: {
            layout: defaultLayout,
            title: "Product Details",
        },
    },
    {
        path: "/categories/:slug",
        name: "category-product",
        component: CategoryProduct,
        meta: {
            layout: defaultLayout,
            title: "Category Products",
        },
    },
    {
        path: "/checkout",
        name: "checkout",
        component: Checkout,
        meta: {
            layout: defaultLayout,
            title: "Checkout",
        },
    },
    {
        path: "/buynow",
        name: "buynow",
        component: BuyNow,
        meta: {
            layout: defaultLayout,
            title: "Buy Now",
        },
    },
    {
        path: "/dashboard",
        name: "dashboard",
        component: Dashboard,
        meta: {
            layout: authLayout,
            title: "Dashboard",
        },
    },
    {
        path: "/order-history",
        name: "order-history",
        component: OrderHistory,
        meta: {
            layout: authLayout,
            title: "Order History",
        },
    },
    {
        path: "/order-history/:id",
        name: "order-details",
        component: OrderDetails,
        meta: {
            layout: authLayout,
            title: "Order Details",
        },
    },
    {
        path: "/wishlist",
        name: "wishlist",
        component: Wishlist,
        meta: {
            layout: authLayout,
            title: "Wishlist",
        },
    },
    {
        path: "/profile",
        name: "profile",
        component: MyProfile,
        meta: {
            layout: authLayout,
            title: "My Profile",
        },
    },
    {
        path: "/manage-address",
        name: "manage-address",
        component: ManageAddress,
        meta: {
            layout: authLayout,
            title: "Manage Address",
        },
    },
    {
        path: "/manage-address/new",
        name: "add-new-address",
        component: AddNewAddress,
        meta: {
            layout: authLayout,
            title: "Add New Address",
        },
    },
    {
        path: "/manage-address/:id/edit",
        name: "edit-address",
        component: EditAddress,
        meta: {
            layout: authLayout,
            title: "Edit Address",
        },
    },
    {
        path: "/change-password",
        name: "change-password",
        component: ChangePassword,
        meta: {
            layout: authLayout,
            title: "Change Password",
        },
    },
    {
        path: "/support-tickets",
        name: "support-ticket",
        component: SupportTicket,
        meta: {
            layout: authLayout,
            title: "Support Ticket",
        },
    },
    {
        path: "/support-ticket/:ticketNumber",
        name: "support-ticket-details",
        component: SupportTicketDetails,
        meta: {
            layout: authLayout,
            title: "Support Ticket Details",
        },
    },

    {
        path: "/support",
        name: "support",
        component: Support,
        meta: {
            layout: defaultLayout,
            title: "Support",
        },
    },
    {
        path: "/terms-and-conditions",
        name: "terms-and-conditions",
        component: TermsAndConditions,
        meta: {
            layout: defaultLayout,
            title: "Terms & Conditions",
        },
    },
    {
        path: "/privacy-policy",
        name: "privacy-policy",
        component: PrivacyPolicy,
        meta: {
            layout: defaultLayout,
            title: "Privacy Policy",
        },
    },
    {
        path: "/about-us",
        name: "about-us",
        component: AboutUs,
        meta: {
            layout: defaultLayout,
            title: "About Us",
        },
    },
    {
        path: "/contact-us",
        name: "contact-us",
        component: ContactUs,
        meta: {
            layout: defaultLayout,
            title: "Contact Us",
        },
    },
    {
        path: "/blogs",
        name: "blogs",
        component: Blog,
        meta: {
            layout: blogLayout,
            title: "Blogs",
        },
    },
    {
        path: "/blog/:slug",
        name: "blog-details",
        component: BlogDetails,
        meta: {
            layout: blogLayout,
            title: "Blog Details",
        },
    },
    {
        path: "/page/:slug",
        name: "policy-page",
        component: PolicyPages,
        meta: {
            layout: defaultLayout,
            title: "Policy Page",
        },
    },
    {
        path: "/massages",
        name: "massages",
        component: Massages,
        meta: {
            layout: authLayout,
            title: "Massages",
        },
    },
    {
        path: "/:pathMatch(.*)*",
        name: "not-found",
        component: NotFound,
        meta: {
            title: "Page Not Found",
            layout: layoutBlank,
        },
    },
];

// create router
const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Dynamic Title for pages
router.beforeEach((to, from, next) => {
    const master = useMaster();
    const appName = master.appName;

    document.title = to.meta.title ? `${to.meta.title} - ${appName}` : appName;
    next();
});

export default router;
