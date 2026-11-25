import './bootstrap';
import { initSidebar } from './modules/sidebar';
import { initHeroBento } from './modules/hero-bento';
import { initFloatingCart } from './modules/floating-cart';
import { initImagePreview } from './modules/image-preview';
import { initTableFilter } from './modules/table-filter';
import { initWilayahForm } from './modules/wilayah-form';
import { initCheckout } from './modules/checkout';
import { initPaymentSnap } from './modules/payment-snap';
import { initOrderReviews } from './modules/order-reviews';
import { initTenantKitchen } from './modules/tenant-kitchen';
import { initTenantOps } from './modules/tenant-ops';
import { initAcademicNotice } from './modules/academic-notice';

document.addEventListener('DOMContentLoaded', () => {
  initSidebar();
  initHeroBento();
  initFloatingCart();
  initImagePreview();
  initTableFilter();
  initWilayahForm();
  initCheckout();
  initPaymentSnap();
  initOrderReviews();
  initTenantKitchen();
  initTenantOps();
  initAcademicNotice();
});
