# File Tree: LAVISHSTAY

Generated on: 8/12/2025, 11:30:54 AM
Root path: `c:\Users\ADMIN\DEV2\LAVISHSTAY`

```
├── 📁 .git/ 🚫 (auto-hidden)
├── 📁 lavishstay-backend/
│   ├── 📁 app/
│   │   ├── 📁 Actions/
│   │   │   ├── 📁 Fortify/
│   │   │   │   ├── 🐘 CreateNewUser.php
│   │   │   │   ├── 🐘 PasswordValidationRules.php
│   │   │   │   ├── 🐘 ResetUserPassword.php
│   │   │   │   ├── 🐘 UpdateUserPassword.php
│   │   │   │   └── 🐘 UpdateUserProfileInformation.php
│   │   │   └── 📁 Jetstream/
│   │   │       └── 🐘 DeleteUser.php
│   │   ├── 📁 Console/
│   │   │   ├── 📁 Commands/
│   │   │   │   ├── 🐘 AutoBookingCleanup.php
│   │   │   │   ├── 🐘 AutoProcessPaymentsCommand.php
│   │   │   │   ├── 🐘 CleanupPendingBookings.php
│   │   │   │   ├── 🐘 ClearPricingCache.php
│   │   │   │   ├── 🐘 DailyRoomOccupancyUpdate.php
│   │   │   │   ├── 🐘 SyncOccupancyData.php
│   │   │   │   ├── 🐘 TestAutoPaymentSystemCommand.php
│   │   │   │   ├── 🐘 TestEmailCommand.php
│   │   │   │   ├── 🐘 TestEmailPatternCommand.php
│   │   │   │   └── 🐘 UpdateOccupancyData.php
│   │   │   └── 🐘 Kernel.php
│   │   ├── 📁 Exceptions/
│   │   │   └── 🐘 Handler.php
│   │   ├── 📁 Http/
│   │   │   ├── 📁 Controllers/
│   │   │   │   ├── 📁 Admin/
│   │   │   │   │   ├── 🐘 CustomerController.php
│   │   │   │   │   ├── 🐘 RoleController.php
│   │   │   │   │   ├── 🐘 RolePermissionController.php
│   │   │   │   │   └── 🐘 StaffController.php
│   │   │   │   ├── 📁 Api/
│   │   │   │   │   ├── 🐘 AuthController.php
│   │   │   │   │   ├── 🐘 BookingCancellationController.php
│   │   │   │   │   ├── 🐘 BookingCheckinController.php
│   │   │   │   │   ├── 🐘 BookingCheckoutController.php
│   │   │   │   │   ├── 🐘 BookingExtensionController.php
│   │   │   │   │   ├── 🐘 BookingRescheduleController.php
│   │   │   │   │   ├── 🐘 BookingTransferController.php
│   │   │   │   │   ├── 🐘 ChartReceptionController.php
│   │   │   │   │   ├── 🐘 ChatController.php
│   │   │   │   │   ├── 🐘 DashboardController.php
│   │   │   │   │   ├── 🐘 FAQController.php
│   │   │   │   │   ├── 🐘 NewsApiController.php
│   │   │   │   │   ├── 🐘 NewsCategoryController.php
│   │   │   │   │   ├── 🐘 NewsCommentController.php
│   │   │   │   │   ├── 🐘 NewsController.php
│   │   │   │   │   ├── 🐘 NewsUserActionController.php
│   │   │   │   │   ├── 🐘 PricingController.php
│   │   │   │   │   ├── 🐘 ReceptionBookController.php
│   │   │   │   │   ├── 🐘 ReceptionController.php
│   │   │   │   │   ├── 🐘 ReviewController.php
│   │   │   │   │   ├── 🐘 RoomAvailabilityController.php
│   │   │   │   │   ├── 🐘 RoomController.php
│   │   │   │   │   ├── 🐘 RoomResquestController.php
│   │   │   │   │   ├── 🐘 RoomTypeController.php
│   │   │   │   │   ├── 🐘 SearchController.php
│   │   │   │   │   ├── 🐘 SitemapController.php
│   │   │   │   │   └── 🗄️ sql.sql
│   │   │   │   ├── 📁 NewsController/
│   │   │   │   │   ├── 🐘 MediaController.php
│   │   │   │   │   ├── 🐘 NewsCategoryController.php
│   │   │   │   │   └── 🐘 NewsController.php
│   │   │   │   ├── 🐘 BookingCancellationController.php
│   │   │   │   ├── 🐘 BookingController.php
│   │   │   │   ├── 🐘 BookingExtensionController.php
│   │   │   │   ├── 🐘 BookingRescheduleController.php
│   │   │   │   ├── 🐘 CancellationPolicyController.php
│   │   │   │   ├── 🐘 ChatSupportController.php
│   │   │   │   ├── 🐘 CheckinPolicyController.php
│   │   │   │   ├── 🐘 CheckoutPolicyController.php
│   │   │   │   ├── 🐘 CheckoutRequestController.php
│   │   │   │   ├── 🐘 ChildPolicyController.php
│   │   │   │   ├── 🐘 Controller.php
│   │   │   │   ├── 🐘 CurrencyController.php
│   │   │   │   ├── 🐘 DashboardController.php
│   │   │   │   ├── 🐘 DataFeedController.php
│   │   │   │   ├── 🐘 DepositPolicyController.php
│   │   │   │   ├── 🐘 DynamicPricingController.php
│   │   │   │   ├── 🐘 EventFestivalManagementController.php
│   │   │   │   ├── 🐘 ExtensionPolicyController.php
│   │   │   │   ├── 🐘 FAQController.php
│   │   │   │   ├── 🐘 FlexiblePricingController.php
│   │   │   │   ├── 🐘 FloorController.php
│   │   │   │   ├── 🐘 LanguageController.php
│   │   │   │   ├── 🐘 PaymentController.php
│   │   │   │   ├── 🐘 PaymentController_v2.php
│   │   │   │   ├── 🐘 PricingManagementController.php
│   │   │   │   ├── 🐘 ReschedulePolicyController.php
│   │   │   │   ├── 🐘 ReviewController.php
│   │   │   │   ├── 🐘 RoomAvailabilityController.php
│   │   │   │   ├── 🐘 RoomController.php
│   │   │   │   ├── 🐘 RoomModificationController.php
│   │   │   │   ├── 🐘 RoomOptionController.php
│   │   │   │   ├── 🐘 RoomPriceEventFestivalController.php
│   │   │   │   ├── 🐘 RoomPriceHistoryController.php
│   │   │   │   ├── 🐘 RoomPricingController.php
│   │   │   │   ├── 🐘 RoomTransferController.php
│   │   │   │   ├── 🐘 RoomTransferPolicyController.php
│   │   │   │   ├── 🐘 RoomTypeAmenityController.php
│   │   │   │   ├── 🐘 RoomTypeController.php
│   │   │   │   ├── 🐘 RoomTypePackageController.php
│   │   │   │   ├── 🐘 RoomTypeServiceController.php
│   │   │   │   ├── 🐘 ServiceAmenityController.php
│   │   │   │   ├── 🐘 ServiceBedController.php
│   │   │   │   ├── 🐘 ServiceController.php
│   │   │   │   ├── 🐘 ServiceMealController.php
│   │   │   │   ├── 🐘 TranslationController.php
│   │   │   │   ├── 🐘 UserController.php
│   │   │   │   ├── 🐘 WeekendPriceController.php
│   │   │   │   └── 📄 sciptcpay
│   │   │   ├── 📁 Middleware/
│   │   │   │   ├── 🐘 Authenticate.php
│   │   │   │   ├── 🐘 CheckPermission.php
│   │   │   │   ├── 🐘 CheckRole.php
│   │   │   │   ├── 🐘 CustomCors.php
│   │   │   │   ├── 🐘 EncryptCookies.php
│   │   │   │   ├── 🐘 PreventRequestsDuringMaintenance.php
│   │   │   │   ├── 🐘 RedirectIfAuthenticated.php
│   │   │   │   ├── 🐘 TrimStrings.php
│   │   │   │   ├── 🐘 TrustHosts.php
│   │   │   │   ├── 🐘 TrustProxies.php
│   │   │   │   ├── 🐘 ValidateSignature.php
│   │   │   │   └── 🐘 VerifyCsrfToken.php
│   │   │   ├── 📁 Requests/
│   │   │   │   ├── 🐘 SearchRoomPackageRequest.php
│   │   │   │   └── 🐘 StoreRoomTypeRequest.php
│   │   │   ├── 📁 Resources/
│   │   │   │   ├── 🐘 BookingResource.php
│   │   │   │   ├── 🐘 RoomOptionResource.php
│   │   │   │   └── 🐘 RoomResource.php
│   │   │   └── 🐘 Kernel.php
│   │   ├── 📁 Jobs/
│   │   │   └── 🐘 AutoProcessPaymentJob.php
│   │   ├── 📁 Mail/
│   │   │   ├── 🐘 BookingConfirmation.php
│   │   │   └── 🐘 ResetPasswordMail.php
│   │   ├── 📁 Models/
│   │   │   ├── 📁 News/
│   │   │   │   ├── 🐘 MediaFile.php
│   │   │   │   ├── 🐘 News.php
│   │   │   │   ├── 🐘 NewsCategory.php
│   │   │   │   ├── 🐘 NewsComment.php
│   │   │   │   └── 🐘 NewsUserAction.php
│   │   │   ├── 🐘 Amenity.php
│   │   │   ├── 🐘 BedType.php
│   │   │   ├── 🐘 Booking.php
│   │   │   ├── 🐘 BookingExtension.php
│   │   │   ├── 🐘 BookingReschedule.php
│   │   │   ├── 🐘 BookingRoom.php
│   │   │   ├── 🐘 BookingRoomChildren.php
│   │   │   ├── 🐘 BookingService.php
│   │   │   ├── 🐘 CancellationPolicy.php
│   │   │   ├── 🐘 CheckInRequest.php
│   │   │   ├── 🐘 CheckinPolicy.php
│   │   │   ├── 🐘 CheckoutPolicy.php
│   │   │   ├── 🐘 CheckoutRequest.php
│   │   │   ├── 🐘 ChildrenSurcharge.php
│   │   │   ├── 🐘 Conversation.php
│   │   │   ├── 🐘 Currency.php
│   │   │   ├── 🐘 DataFeed.php
│   │   │   ├── 🐘 DepositPolicy.php
│   │   │   ├── 🐘 DynamicPricingRule.php
│   │   │   ├── 🐘 Event.php
│   │   │   ├── 🐘 ExtensionPolicy.php
│   │   │   ├── 🐘 ExtensionRequest.php
│   │   │   ├── 🐘 FAQ.php
│   │   │   ├── 🐘 FlexiblePricingRule.php
│   │   │   ├── 🐘 Floor.php
│   │   │   ├── 🐘 Holiday.php
│   │   │   ├── 🐘 Hotel.php
│   │   │   ├── 🐘 Invoice.php
│   │   │   ├── 🐘 Language.php
│   │   │   ├── 🐘 MealType.php
│   │   │   ├── 🐘 MediaFile.php
│   │   │   ├── 🐘 Message.php
│   │   │   ├── 🐘 Payment.php
│   │   │   ├── 🐘 Permission.php
│   │   │   ├── 🐘 PolicyApplication.php
│   │   │   ├── 🐘 PricingConfig.php
│   │   │   ├── 🐘 Representative.php
│   │   │   ├── 🐘 ReschedulePolicy.php
│   │   │   ├── 🐘 Review.php
│   │   │   ├── 🐘 Role.php
│   │   │   ├── 🐘 Room.php
│   │   │   ├── 🐘 RoomAvailability.php
│   │   │   ├── 🐘 RoomBedOption.php
│   │   │   ├── 🐘 RoomMealOption.php
│   │   │   ├── 🐘 RoomModification.php
│   │   │   ├── 🐘 RoomOccupancy.php
│   │   │   ├── 🐘 RoomOption.php
│   │   │   ├── 🐘 RoomOptionPromotion.php
│   │   │   ├── 🐘 RoomPrice.php
│   │   │   ├── 🐘 RoomPriceHistory.php
│   │   │   ├── 🐘 RoomPricing.php
│   │   │   ├── 🐘 RoomTransfer.php
│   │   │   ├── 🐘 RoomTransferPolicy.php
│   │   │   ├── 🐘 RoomType.php
│   │   │   ├── 🐘 RoomTypeAmenity.php
│   │   │   ├── 🐘 RoomTypeImage.php
│   │   │   ├── 🐘 RoomTypePackage.php
│   │   │   ├── 🐘 Service.php
│   │   │   ├── 🐘 TableTranslation.php
│   │   │   ├── 🐘 Translation.php
│   │   │   ├── 🐘 User.php
│   │   │   └── 🐘 WeekendDay.php
│   │   ├── 📁 Providers/
│   │   │   ├── 🐘 AppServiceProvider.php
│   │   │   ├── 🐘 AuthServiceProvider.php
│   │   │   ├── 🐘 BroadcastServiceProvider.php
│   │   │   ├── 🐘 EventServiceProvider.php
│   │   │   ├── 🐘 FortifyServiceProvider.php
│   │   │   ├── 🐘 JetstreamServiceProvider.php
│   │   │   ├── 🐘 PricingServiceProvider.php
│   │   │   └── 🐘 RouteServiceProvider.php
│   │   ├── 📁 Services/
│   │   │   ├── 🐘 ChatBotService.php
│   │   │   ├── 🐘 DashboardService.php
│   │   │   ├── 🐘 DynamicPricingService.php
│   │   │   ├── 🐘 PolicySelectorService.php
│   │   │   ├── 🐘 PricingService.php
│   │   │   ├── 🐘 RoomAvailabilityService.php
│   │   │   └── 🐘 RoomOccupancyService.php
│   │   └── 📁 View/
│   │       └── 📁 Components/
│   │           ├── 🐘 AppLayout.php
│   │           ├── 🐘 AuthenticationLayout.php
│   │           ├── 🐘 EmptyLayout.php
│   │           ├── 🐘 GuestLayout.php
│   │           └── 🐘 RoomCalendarModal.php
│   ├── 📁 bootstrap/
│   │   ├── 📁 cache/ 🚫 (auto-hidden)
│   │   └── 🐘 app.php
│   ├── 📁 config/
│   │   ├── 🐘 app.php
│   │   ├── 🐘 auth.php
│   │   ├── 🐘 broadcasting.php
│   │   ├── 🐘 cache.php
│   │   ├── 🐘 cors.php
│   │   ├── 🐘 database.php
│   │   ├── 🐘 filesystems.php
│   │   ├── 🐘 fortify.php
│   │   ├── 🐘 google.php
│   │   ├── 🐘 hashing.php
│   │   ├── 🐘 jetstream.php
│   │   ├── 🐘 livewire.php
│   │   ├── 🐘 logging.php
│   │   ├── 🐘 mail.php
│   │   ├── 🐘 queue.php
│   │   ├── 🐘 sanctum.php
│   │   ├── 🐘 services.php
│   │   ├── 🐘 session.php
│   │   ├── 🐘 view.php
│   │   └── 🐘 vnpay.php
│   ├── 📁 database/
│   │   ├── 📁 db_quyen/
│   │   │   ├── 🗄️ 00_master_setup.sql
│   │   │   ├── 🗄️ 01_schema_core.sql
│   │   │   ├── 🗄️ 02_schema_amenities.sql
│   │   │   ├── 🗄️ 03_schema_booking_services.sql
│   │   │   ├── 🗄️ 04_schema_operations.sql
│   │   │   ├── 🗄️ 05_schema_policies.sql
│   │   │   ├── 🗄️ 06_schema_guest_reviews.sql
│   │   │   ├── 🗄️ 07_schema_voucher.sql
│   │   │   ├── 🗄️ 08_schema_pricing.sql
│   │   │   ├── 🗄️ 09_schema_media.sql
│   │   │   ├── 🗄️ 10_data_basic.sql
│   │   │   ├── 🗄️ 11_data_rooms_complete.sql
│   │   │   ├── 🗄️ 12_data_amenities.sql
│   │   │   ├── 🗄️ 13_data_policies.sql
│   │   │   ├── 🗄️ 14_data_guest_reviews.sql
│   │   │   ├── 🗄️ 15_data_voucher.sql
│   │   │   ├── 🗄️ 16_data_pricing.sql
│   │   │   ├── 🗄️ 17_data_booking_samples.sql
│   │   │   ├── 🗄️ 18_data_media.sql
│   │   │   ├── 🗄️ 18_data_operations.sql
│   │   │   ├── 🗄️ 20_procedures_policies.sql
│   │   │   ├── 🗄️ 21_procedures_pricing.sql
│   │   │   ├── 🗄️ 22_views_reports.sql
│   │   │   ├── 🗄️ 22b_policy_views.sql
│   │   │   ├── 🗄️ 23_triggers_validations.sql
│   │   │   ├── 🐚 run_setup.bat
│   │   │   ├── 🗄️ search_rooms_query.sql
│   │   │   └── 📄 tableSeached.txt
│   │   ├── 📁 factories/
│   │   │   └── 🐘 UserFactory.php
│   │   ├── 📁 migrations/
│   │   │   ├── 🐘 2024_01_15_100000_create_news_comments_table.php
│   │   │   └── 🐘 2024_01_15_110000_create_news_user_actions_table.php
│   │   ├── 📁 seeders/
│   │   │   ├── 🐘 ChildrenSurchargeSeeder.php
│   │   │   ├── 🐘 DashboardTableSeeder.php
│   │   │   ├── 🐘 DatabaseSeeder.php
│   │   │   ├── 🐘 EventHolidaySeeder.php
│   │   │   ├── 🐘 FaqSeeder.php
│   │   │   ├── 🐘 HotelSeeder.php
│   │   │   ├── 🐘 MediaFileSeeder.php
│   │   │   ├── 🐘 NewsCategorySeeder.php
│   │   │   ├── 🐘 NewsCommentSeeder.php
│   │   │   ├── 🐘 NewsModuleSeeder.php
│   │   │   ├── 🐘 NewsSeeder.php
│   │   │   ├── 🐘 NewsUserActionSeeder.php
│   │   │   └── 🐘 PolicySeeder.php
│   │   └── 🚫 .gitignore
│   ├── 📁 lang/
│   │   └── 📁 en/
│   │       ├── 🐘 auth.php
│   │       ├── 🐘 pagination.php
│   │       ├── 🐘 passwords.php
│   │       └── 🐘 validation.php
│   ├── 📁 node_modules/ 🚫 (auto-hidden)
│   ├── 📁 public/
│   │   ├── 📁 build/ 🚫 (auto-hidden)
│   │   ├── 📁 ckeditor1/
│   │   │   ├── 📁 adapters/
│   │   │   │   └── 📄 jquery.js
│   │   │   ├── 📁 lang/
│   │   │   │   ├── 📄 af.js
│   │   │   │   ├── 📄 ar.js
│   │   │   │   ├── 📄 az.js
│   │   │   │   ├── 📄 bg.js
│   │   │   │   ├── 📄 bn.js
│   │   │   │   ├── 📄 bs.js
│   │   │   │   ├── 📄 ca.js
│   │   │   │   ├── 📄 cs.js
│   │   │   │   ├── 📄 cy.js
│   │   │   │   ├── 📄 da.js
│   │   │   │   ├── 📄 de-ch.js
│   │   │   │   ├── 📄 de.js
│   │   │   │   ├── 📄 el.js
│   │   │   │   ├── 📄 en-au.js
│   │   │   │   ├── 📄 en-ca.js
│   │   │   │   ├── 📄 en-gb.js
│   │   │   │   ├── 📄 en.js
│   │   │   │   ├── 📄 eo.js
│   │   │   │   ├── 📄 es-mx.js
│   │   │   │   ├── 📄 es.js
│   │   │   │   ├── 📄 et.js
│   │   │   │   ├── 📄 eu.js
│   │   │   │   ├── 📄 fa.js
│   │   │   │   ├── 📄 fi.js
│   │   │   │   ├── 📄 fo.js
│   │   │   │   ├── 📄 fr-ca.js
│   │   │   │   ├── 📄 fr.js
│   │   │   │   ├── 📄 gl.js
│   │   │   │   ├── 📄 gu.js
│   │   │   │   ├── 📄 he.js
│   │   │   │   ├── 📄 hi.js
│   │   │   │   ├── 📄 hr.js
│   │   │   │   ├── 📄 hu.js
│   │   │   │   ├── 📄 id.js
│   │   │   │   ├── 📄 is.js
│   │   │   │   ├── 📄 it.js
│   │   │   │   ├── 📄 ja.js
│   │   │   │   ├── 📄 ka.js
│   │   │   │   ├── 📄 km.js
│   │   │   │   ├── 📄 ko.js
│   │   │   │   ├── 📄 ku.js
│   │   │   │   ├── 📄 lt.js
│   │   │   │   ├── 📄 lv.js
│   │   │   │   ├── 📄 mk.js
│   │   │   │   ├── 📄 mn.js
│   │   │   │   ├── 📄 ms.js
│   │   │   │   ├── 📄 nb.js
│   │   │   │   ├── 📄 nl.js
│   │   │   │   ├── 📄 no.js
│   │   │   │   ├── 📄 oc.js
│   │   │   │   ├── 📄 pl.js
│   │   │   │   ├── 📄 pt-br.js
│   │   │   │   ├── 📄 pt.js
│   │   │   │   ├── 📄 ro.js
│   │   │   │   ├── 📄 ru.js
│   │   │   │   ├── 📄 si.js
│   │   │   │   ├── 📄 sk.js
│   │   │   │   ├── 📄 sl.js
│   │   │   │   ├── 📄 sq.js
│   │   │   │   ├── 📄 sr-latn.js
│   │   │   │   ├── 📄 sr.js
│   │   │   │   ├── 📄 sv.js
│   │   │   │   ├── 📄 th.js
│   │   │   │   ├── 📄 tr.js
│   │   │   │   ├── 📄 tt.js
│   │   │   │   ├── 📄 ug.js
│   │   │   │   ├── 📄 uk.js
│   │   │   │   ├── 📄 vi.js
│   │   │   │   ├── 📄 zh-cn.js
│   │   │   │   └── 📄 zh.js
│   │   │   ├── 📁 plugins/
│   │   │   │   ├── 📁 a11yhelp/
│   │   │   │   │   └── 📁 dialogs/
│   │   │   │   │       ├── 📁 lang/
│   │   │   │   │       │   ├── 📄 _translationstatus.txt
│   │   │   │   │       │   ├── 📄 af.js
│   │   │   │   │       │   ├── 📄 ar.js
│   │   │   │   │       │   ├── 📄 az.js
│   │   │   │   │       │   ├── 📄 bg.js
│   │   │   │   │       │   ├── 📄 ca.js
│   │   │   │   │       │   ├── 📄 cs.js
│   │   │   │   │       │   ├── 📄 cy.js
│   │   │   │   │       │   ├── 📄 da.js
│   │   │   │   │       │   ├── 📄 de-ch.js
│   │   │   │   │       │   ├── 📄 de.js
│   │   │   │   │       │   ├── 📄 el.js
│   │   │   │   │       │   ├── 📄 en-au.js
│   │   │   │   │       │   ├── 📄 en-gb.js
│   │   │   │   │       │   ├── 📄 en.js
│   │   │   │   │       │   ├── 📄 eo.js
│   │   │   │   │       │   ├── 📄 es-mx.js
│   │   │   │   │       │   ├── 📄 es.js
│   │   │   │   │       │   ├── 📄 et.js
│   │   │   │   │       │   ├── 📄 eu.js
│   │   │   │   │       │   ├── 📄 fa.js
│   │   │   │   │       │   ├── 📄 fi.js
│   │   │   │   │       │   ├── 📄 fo.js
│   │   │   │   │       │   ├── 📄 fr-ca.js
│   │   │   │   │       │   ├── 📄 fr.js
│   │   │   │   │       │   ├── 📄 gl.js
│   │   │   │   │       │   ├── 📄 gu.js
│   │   │   │   │       │   ├── 📄 he.js
│   │   │   │   │       │   ├── 📄 hi.js
│   │   │   │   │       │   ├── 📄 hr.js
│   │   │   │   │       │   ├── 📄 hu.js
│   │   │   │   │       │   ├── 📄 id.js
│   │   │   │   │       │   ├── 📄 it.js
│   │   │   │   │       │   ├── 📄 ja.js
│   │   │   │   │       │   ├── 📄 km.js
│   │   │   │   │       │   ├── 📄 ko.js
│   │   │   │   │       │   ├── 📄 ku.js
│   │   │   │   │       │   ├── 📄 lt.js
│   │   │   │   │       │   ├── 📄 lv.js
│   │   │   │   │       │   ├── 📄 mk.js
│   │   │   │   │       │   ├── 📄 mn.js
│   │   │   │   │       │   ├── 📄 nb.js
│   │   │   │   │       │   ├── 📄 nl.js
│   │   │   │   │       │   ├── 📄 no.js
│   │   │   │   │       │   ├── 📄 oc.js
│   │   │   │   │       │   ├── 📄 pl.js
│   │   │   │   │       │   ├── 📄 pt-br.js
│   │   │   │   │       │   ├── 📄 pt.js
│   │   │   │   │       │   ├── 📄 ro.js
│   │   │   │   │       │   ├── 📄 ru.js
│   │   │   │   │       │   ├── 📄 si.js
│   │   │   │   │       │   ├── 📄 sk.js
│   │   │   │   │       │   ├── 📄 sl.js
│   │   │   │   │       │   ├── 📄 sq.js
│   │   │   │   │       │   ├── 📄 sr-latn.js
│   │   │   │   │       │   ├── 📄 sr.js
│   │   │   │   │       │   ├── 📄 sv.js
│   │   │   │   │       │   ├── 📄 th.js
│   │   │   │   │       │   ├── 📄 tr.js
│   │   │   │   │       │   ├── 📄 tt.js
│   │   │   │   │       │   ├── 📄 ug.js
│   │   │   │   │       │   ├── 📄 uk.js
│   │   │   │   │       │   ├── 📄 vi.js
│   │   │   │   │       │   ├── 📄 zh-cn.js
│   │   │   │   │       │   └── 📄 zh.js
│   │   │   │   │       └── 📄 a11yhelp.js
│   │   │   │   ├── 📁 about/
│   │   │   │   │   └── 📁 dialogs/
│   │   │   │   │       ├── 📁 hidpi/
│   │   │   │   │       │   └── 🖼️ logo_ckeditor.png
│   │   │   │   │       ├── 📄 about.js
│   │   │   │   │       └── 🖼️ logo_ckeditor.png
│   │   │   │   ├── 📁 clipboard/
│   │   │   │   │   └── 📁 dialogs/
│   │   │   │   │       └── 📄 paste.js
│   │   │   │   ├── 📁 colordialog/
│   │   │   │   │   └── 📁 dialogs/
│   │   │   │   │       ├── 🎨 colordialog.css
│   │   │   │   │       └── 📄 colordialog.js
│   │   │   │   ├── 📁 copyformatting/
│   │   │   │   │   ├── 📁 cursors/
│   │   │   │   │   │   ├── 🖼️ cursor-disabled.svg
│   │   │   │   │   │   └── 🖼️ cursor.svg
│   │   │   │   │   └── 📁 styles/
│   │   │   │   │       └── 🎨 copyformatting.css
│   │   │   │   ├── 📁 dialog/
│   │   │   │   │   ├── 📁 styles/
│   │   │   │   │   │   └── 🎨 dialog.css
│   │   │   │   │   └── 📄 dialogDefinition.js
│   │   │   │   ├── 📁 div/
│   │   │   │   │   └── 📁 dialogs/
│   │   │   │   │       └── 📄 div.js
│   │   │   │   ├── 📁 exportpdf/
│   │   │   │   │   ├── 📁 tests/
│   │   │   │   │   │   ├── 📁 _helpers/
│   │   │   │   │   │   │   └── 📄 tools.js
│   │   │   │   │   │   ├── 📁 manual/
│   │   │   │   │   │   │   ├── 📁 integrations/
│   │   │   │   │   │   │   │   ├── 🌐 easyimage.html
│   │   │   │   │   │   │   │   └── 📝 easyimage.md
│   │   │   │   │   │   │   ├── 🌐 configfilename.html
│   │   │   │   │   │   │   ├── 📝 configfilename.md
│   │   │   │   │   │   │   ├── 🌐 emptyeditor.html
│   │   │   │   │   │   │   ├── 📝 emptyeditor.md
│   │   │   │   │   │   │   ├── 🌐 integration.html
│   │   │   │   │   │   │   ├── 📝 integration.md
│   │   │   │   │   │   │   ├── 🌐 notifications.html
│   │   │   │   │   │   │   ├── 📝 notifications.md
│   │   │   │   │   │   │   ├── 🌐 notificationsasync.html
│   │   │   │   │   │   │   ├── 📝 notificationsasync.md
│   │   │   │   │   │   │   ├── 🌐 paperformat.html
│   │   │   │   │   │   │   ├── 📝 paperformat.md
│   │   │   │   │   │   │   ├── 🌐 readonly.html
│   │   │   │   │   │   │   ├── 📝 readonly.md
│   │   │   │   │   │   │   ├── 🌐 stylesheets.html
│   │   │   │   │   │   │   ├── 📝 stylesheets.md
│   │   │   │   │   │   │   ├── 🌐 tokenfetching.html
│   │   │   │   │   │   │   ├── 📝 tokenfetching.md
│   │   │   │   │   │   │   ├── 🌐 tokentwoeditorscorrect.html
│   │   │   │   │   │   │   ├── 📝 tokentwoeditorscorrect.md
│   │   │   │   │   │   │   ├── 🌐 tokentwoeditorswrong.html
│   │   │   │   │   │   │   ├── 📝 tokentwoeditorswrong.md
│   │   │   │   │   │   │   ├── 🌐 tokenwithouturl.html
│   │   │   │   │   │   │   ├── 📝 tokenwithouturl.md
│   │   │   │   │   │   │   ├── 🌐 wrongendpoint.html
│   │   │   │   │   │   │   └── 📝 wrongendpoint.md
│   │   │   │   │   │   ├── 📄 authentication.js
│   │   │   │   │   │   ├── 📄 exportpdf.js
│   │   │   │   │   │   ├── 📄 notification.js
│   │   │   │   │   │   ├── 📄 resourcespaths.js
│   │   │   │   │   │   ├── 📄 statistics.js
│   │   │   │   │   │   └── 📄 stylesheets.js
│   │   │   │   │   ├── 📝 CHANGELOG.md
│   │   │   │   │   ├── 📜 LICENSE.md
│   │   │   │   │   ├── 📖 README.md
│   │   │   │   │   └── 📄 plugindefinition.js
│   │   │   │   ├── 📁 find/
│   │   │   │   │   └── 📁 dialogs/
│   │   │   │   │       └── 📄 find.js
│   │   │   │   ├── 📁 forms/
│   │   │   │   │   ├── 📁 dialogs/
│   │   │   │   │   │   ├── 📄 button.js
│   │   │   │   │   │   ├── 📄 checkbox.js
│   │   │   │   │   │   ├── 📄 form.js
│   │   │   │   │   │   ├── 📄 hiddenfield.js
│   │   │   │   │   │   ├── 📄 radio.js
│   │   │   │   │   │   ├── 📄 select.js
│   │   │   │   │   │   ├── 📄 textarea.js
│   │   │   │   │   │   └── 📄 textfield.js
│   │   │   │   │   └── 📁 images/
│   │   │   │   │       └── 🖼️ hiddenfield.gif
│   │   │   │   ├── 📁 iframe/
│   │   │   │   │   ├── 📁 dialogs/
│   │   │   │   │   │   └── 📄 iframe.js
│   │   │   │   │   └── 📁 images/
│   │   │   │   │       └── 🖼️ placeholder.png
│   │   │   │   ├── 📁 image/
│   │   │   │   │   ├── 📁 dialogs/
│   │   │   │   │   │   └── 📄 image.js
│   │   │   │   │   └── 📁 images/
│   │   │   │   │       └── 🖼️ noimage.png
│   │   │   │   ├── 📁 link/
│   │   │   │   │   ├── 📁 dialogs/
│   │   │   │   │   │   ├── 📄 anchor.js
│   │   │   │   │   │   └── 📄 link.js
│   │   │   │   │   └── 📁 images/
│   │   │   │   │       ├── 📁 hidpi/
│   │   │   │   │       │   └── 🖼️ anchor.png
│   │   │   │   │       └── 🖼️ anchor.png
│   │   │   │   ├── 📁 liststyle/
│   │   │   │   │   └── 📁 dialogs/
│   │   │   │   │       └── 📄 liststyle.js
│   │   │   │   ├── 📁 magicline/
│   │   │   │   │   └── 📁 images/
│   │   │   │   │       ├── 📁 hidpi/
│   │   │   │   │       │   ├── 🖼️ icon-rtl.png
│   │   │   │   │       │   └── 🖼️ icon.png
│   │   │   │   │       ├── 🖼️ icon-rtl.png
│   │   │   │   │       └── 🖼️ icon.png
│   │   │   │   ├── 📁 pagebreak/
│   │   │   │   │   └── 📁 images/
│   │   │   │   │       └── 🖼️ pagebreak.gif
│   │   │   │   ├── 📁 pastefromgdocs/
│   │   │   │   │   └── 📁 filter/
│   │   │   │   │       └── 📄 default.js
│   │   │   │   ├── 📁 pastefromlibreoffice/
│   │   │   │   │   └── 📁 filter/
│   │   │   │   │       └── 📄 default.js
│   │   │   │   ├── 📁 pastefromword/
│   │   │   │   │   └── 📁 filter/
│   │   │   │   │       └── 📄 default.js
│   │   │   │   ├── 📁 pastetools/
│   │   │   │   │   └── 📁 filter/
│   │   │   │   │       ├── 📄 common.js
│   │   │   │   │       └── 📄 image.js
│   │   │   │   ├── 📁 preview/
│   │   │   │   │   ├── 📁 images/
│   │   │   │   │   │   └── 🖼️ pagebreak.gif
│   │   │   │   │   ├── 📁 styles/
│   │   │   │   │   │   └── 🎨 screen.css
│   │   │   │   │   └── 🌐 preview.html
│   │   │   │   ├── 📁 scayt/
│   │   │   │   │   ├── 📁 dialogs/
│   │   │   │   │   │   ├── 🎨 dialog.css
│   │   │   │   │   │   ├── 📄 options.js
│   │   │   │   │   │   └── 🎨 toolbar.css
│   │   │   │   │   ├── 📁 skins/
│   │   │   │   │   │   └── 📁 moono-lisa/
│   │   │   │   │   │       └── 🎨 scayt.css
│   │   │   │   │   ├── 📝 CHANGELOG.md
│   │   │   │   │   ├── 📜 LICENSE.md
│   │   │   │   │   └── 📖 README.md
│   │   │   │   ├── 📁 showblocks/
│   │   │   │   │   └── 📁 images/
│   │   │   │   │       ├── 🖼️ block_address.png
│   │   │   │   │       ├── 🖼️ block_blockquote.png
│   │   │   │   │       ├── 🖼️ block_div.png
│   │   │   │   │       ├── 🖼️ block_h1.png
│   │   │   │   │       ├── 🖼️ block_h2.png
│   │   │   │   │       ├── 🖼️ block_h3.png
│   │   │   │   │       ├── 🖼️ block_h4.png
│   │   │   │   │       ├── 🖼️ block_h5.png
│   │   │   │   │       ├── 🖼️ block_h6.png
│   │   │   │   │       ├── 🖼️ block_p.png
│   │   │   │   │       └── 🖼️ block_pre.png
│   │   │   │   ├── 📁 smiley/
│   │   │   │   │   ├── 📁 dialogs/
│   │   │   │   │   │   └── 📄 smiley.js
│   │   │   │   │   └── 📁 images/
│   │   │   │   │       ├── 🖼️ angel_smile.gif
│   │   │   │   │       ├── 🖼️ angel_smile.png
│   │   │   │   │       ├── 🖼️ angry_smile.gif
│   │   │   │   │       ├── 🖼️ angry_smile.png
│   │   │   │   │       ├── 🖼️ broken_heart.gif
│   │   │   │   │       ├── 🖼️ broken_heart.png
│   │   │   │   │       ├── 🖼️ confused_smile.gif
│   │   │   │   │       ├── 🖼️ confused_smile.png
│   │   │   │   │       ├── 🖼️ cry_smile.gif
│   │   │   │   │       ├── 🖼️ cry_smile.png
│   │   │   │   │       ├── 🖼️ devil_smile.gif
│   │   │   │   │       ├── 🖼️ devil_smile.png
│   │   │   │   │       ├── 🖼️ embaressed_smile.gif
│   │   │   │   │       ├── 🖼️ embarrassed_smile.gif
│   │   │   │   │       ├── 🖼️ embarrassed_smile.png
│   │   │   │   │       ├── 🖼️ envelope.gif
│   │   │   │   │       ├── 🖼️ envelope.png
│   │   │   │   │       ├── 🖼️ heart.gif
│   │   │   │   │       ├── 🖼️ heart.png
│   │   │   │   │       ├── 🖼️ kiss.gif
│   │   │   │   │       ├── 🖼️ kiss.png
│   │   │   │   │       ├── 🖼️ lightbulb.gif
│   │   │   │   │       ├── 🖼️ lightbulb.png
│   │   │   │   │       ├── 🖼️ omg_smile.gif
│   │   │   │   │       ├── 🖼️ omg_smile.png
│   │   │   │   │       ├── 🖼️ regular_smile.gif
│   │   │   │   │       ├── 🖼️ regular_smile.png
│   │   │   │   │       ├── 🖼️ sad_smile.gif
│   │   │   │   │       ├── 🖼️ sad_smile.png
│   │   │   │   │       ├── 🖼️ shades_smile.gif
│   │   │   │   │       ├── 🖼️ shades_smile.png
│   │   │   │   │       ├── 🖼️ teeth_smile.gif
│   │   │   │   │       ├── 🖼️ teeth_smile.png
│   │   │   │   │       ├── 🖼️ thumbs_down.gif
│   │   │   │   │       ├── 🖼️ thumbs_down.png
│   │   │   │   │       ├── 🖼️ thumbs_up.gif
│   │   │   │   │       ├── 🖼️ thumbs_up.png
│   │   │   │   │       ├── 🖼️ tongue_smile.gif
│   │   │   │   │       ├── 🖼️ tongue_smile.png
│   │   │   │   │       ├── 🖼️ tounge_smile.gif
│   │   │   │   │       ├── 🖼️ whatchutalkingabout_smile.gif
│   │   │   │   │       ├── 🖼️ whatchutalkingabout_smile.png
│   │   │   │   │       ├── 🖼️ wink_smile.gif
│   │   │   │   │       └── 🖼️ wink_smile.png
│   │   │   │   ├── 📁 specialchar/
│   │   │   │   │   └── 📁 dialogs/
│   │   │   │   │       ├── 📁 lang/
│   │   │   │   │       │   ├── 📄 _translationstatus.txt
│   │   │   │   │       │   ├── 📄 af.js
│   │   │   │   │       │   ├── 📄 ar.js
│   │   │   │   │       │   ├── 📄 az.js
│   │   │   │   │       │   ├── 📄 bg.js
│   │   │   │   │       │   ├── 📄 ca.js
│   │   │   │   │       │   ├── 📄 cs.js
│   │   │   │   │       │   ├── 📄 cy.js
│   │   │   │   │       │   ├── 📄 da.js
│   │   │   │   │       │   ├── 📄 de-ch.js
│   │   │   │   │       │   ├── 📄 de.js
│   │   │   │   │       │   ├── 📄 el.js
│   │   │   │   │       │   ├── 📄 en-au.js
│   │   │   │   │       │   ├── 📄 en-ca.js
│   │   │   │   │       │   ├── 📄 en-gb.js
│   │   │   │   │       │   ├── 📄 en.js
│   │   │   │   │       │   ├── 📄 eo.js
│   │   │   │   │       │   ├── 📄 es-mx.js
│   │   │   │   │       │   ├── 📄 es.js
│   │   │   │   │       │   ├── 📄 et.js
│   │   │   │   │       │   ├── 📄 eu.js
│   │   │   │   │       │   ├── 📄 fa.js
│   │   │   │   │       │   ├── 📄 fi.js
│   │   │   │   │       │   ├── 📄 fr-ca.js
│   │   │   │   │       │   ├── 📄 fr.js
│   │   │   │   │       │   ├── 📄 gl.js
│   │   │   │   │       │   ├── 📄 he.js
│   │   │   │   │       │   ├── 📄 hr.js
│   │   │   │   │       │   ├── 📄 hu.js
│   │   │   │   │       │   ├── 📄 id.js
│   │   │   │   │       │   ├── 📄 it.js
│   │   │   │   │       │   ├── 📄 ja.js
│   │   │   │   │       │   ├── 📄 km.js
│   │   │   │   │       │   ├── 📄 ko.js
│   │   │   │   │       │   ├── 📄 ku.js
│   │   │   │   │       │   ├── 📄 lt.js
│   │   │   │   │       │   ├── 📄 lv.js
│   │   │   │   │       │   ├── 📄 nb.js
│   │   │   │   │       │   ├── 📄 nl.js
│   │   │   │   │       │   ├── 📄 no.js
│   │   │   │   │       │   ├── 📄 oc.js
│   │   │   │   │       │   ├── 📄 pl.js
│   │   │   │   │       │   ├── 📄 pt-br.js
│   │   │   │   │       │   ├── 📄 pt.js
│   │   │   │   │       │   ├── 📄 ro.js
│   │   │   │   │       │   ├── 📄 ru.js
│   │   │   │   │       │   ├── 📄 si.js
│   │   │   │   │       │   ├── 📄 sk.js
│   │   │   │   │       │   ├── 📄 sl.js
│   │   │   │   │       │   ├── 📄 sq.js
│   │   │   │   │       │   ├── 📄 sr-latn.js
│   │   │   │   │       │   ├── 📄 sr.js
│   │   │   │   │       │   ├── 📄 sv.js
│   │   │   │   │       │   ├── 📄 th.js
│   │   │   │   │       │   ├── 📄 tr.js
│   │   │   │   │       │   ├── 📄 tt.js
│   │   │   │   │       │   ├── 📄 ug.js
│   │   │   │   │       │   ├── 📄 uk.js
│   │   │   │   │       │   ├── 📄 vi.js
│   │   │   │   │       │   ├── 📄 zh-cn.js
│   │   │   │   │       │   └── 📄 zh.js
│   │   │   │   │       └── 📄 specialchar.js
│   │   │   │   ├── 📁 table/
│   │   │   │   │   └── 📁 dialogs/
│   │   │   │   │       └── 📄 table.js
│   │   │   │   ├── 📁 tableselection/
│   │   │   │   │   └── 📁 styles/
│   │   │   │   │       └── 🎨 tableselection.css
│   │   │   │   ├── 📁 tabletools/
│   │   │   │   │   └── 📁 dialogs/
│   │   │   │   │       └── 📄 tableCell.js
│   │   │   │   ├── 📁 templates/
│   │   │   │   │   ├── 📁 dialogs/
│   │   │   │   │   │   ├── 🎨 templates.css
│   │   │   │   │   │   └── 📄 templates.js
│   │   │   │   │   ├── 📁 templates/
│   │   │   │   │   │   ├── 📁 images/
│   │   │   │   │   │   │   ├── 🖼️ template1.gif
│   │   │   │   │   │   │   ├── 🖼️ template2.gif
│   │   │   │   │   │   │   └── 🖼️ template3.gif
│   │   │   │   │   │   └── 📄 default.js
│   │   │   │   │   └── 📄 templatedefinition.js
│   │   │   │   ├── 📁 widget/
│   │   │   │   │   └── 📁 images/
│   │   │   │   │       └── 🖼️ handle.png
│   │   │   │   ├── 🖼️ icons.png
│   │   │   │   └── 🖼️ icons_hidpi.png
│   │   │   ├── 📁 samples/
│   │   │   │   ├── 📁 css/
│   │   │   │   │   └── 🎨 samples.css
│   │   │   │   ├── 📁 img/
│   │   │   │   │   ├── 🖼️ github-top.png
│   │   │   │   │   ├── 🖼️ header-bg.png
│   │   │   │   │   ├── 🖼️ header-separator.png
│   │   │   │   │   ├── 🖼️ logo.png
│   │   │   │   │   ├── 🖼️ logo.svg
│   │   │   │   │   └── 🖼️ navigation-tip.png
│   │   │   │   ├── 📁 js/
│   │   │   │   │   ├── 📄 sample.js
│   │   │   │   │   └── 📄 sf.js
│   │   │   │   ├── 📁 old/
│   │   │   │   │   ├── 📁 assets/
│   │   │   │   │   │   ├── 📁 inlineall/
│   │   │   │   │   │   │   └── 🖼️ logo.png
│   │   │   │   │   │   ├── 📁 outputxhtml/
│   │   │   │   │   │   │   └── 🎨 outputxhtml.css
│   │   │   │   │   │   ├── 📁 uilanguages/
│   │   │   │   │   │   │   └── 📄 languages.js
│   │   │   │   │   │   ├── 🐘 posteddata.php
│   │   │   │   │   │   └── 🖼️ sample.jpg
│   │   │   │   │   ├── 📁 dialog/
│   │   │   │   │   │   ├── 📁 assets/
│   │   │   │   │   │   │   └── 📄 my_dialog.js
│   │   │   │   │   │   └── 🌐 dialog.html
│   │   │   │   │   ├── 📁 enterkey/
│   │   │   │   │   │   └── 🌐 enterkey.html
│   │   │   │   │   ├── 📁 htmlwriter/
│   │   │   │   │   │   └── 🌐 outputhtml.html
│   │   │   │   │   ├── 📁 magicline/
│   │   │   │   │   │   └── 🌐 magicline.html
│   │   │   │   │   ├── 📁 toolbar/
│   │   │   │   │   │   └── 🌐 toolbar.html
│   │   │   │   │   ├── 📁 wysiwygarea/
│   │   │   │   │   │   └── 🌐 fullpage.html
│   │   │   │   │   ├── 🌐 ajax.html
│   │   │   │   │   ├── 🌐 api.html
│   │   │   │   │   ├── 🌐 appendto.html
│   │   │   │   │   ├── 🌐 datafiltering.html
│   │   │   │   │   ├── 🌐 divreplace.html
│   │   │   │   │   ├── 🌐 index.html
│   │   │   │   │   ├── 🌐 inlineall.html
│   │   │   │   │   ├── 🌐 inlinebycode.html
│   │   │   │   │   ├── 🌐 inlinetextarea.html
│   │   │   │   │   ├── 🌐 jquery.html
│   │   │   │   │   ├── 🌐 readonly.html
│   │   │   │   │   ├── 🌐 replacebyclass.html
│   │   │   │   │   ├── 🌐 replacebycode.html
│   │   │   │   │   ├── 🎨 sample.css
│   │   │   │   │   ├── 📄 sample.js
│   │   │   │   │   ├── 🐘 sample_posteddata.php
│   │   │   │   │   ├── 🌐 tabindex.html
│   │   │   │   │   ├── 🌐 uicolor.html
│   │   │   │   │   ├── 🌐 uilanguages.html
│   │   │   │   │   └── 🌐 xhtmlstyle.html
│   │   │   │   ├── 📁 toolbarconfigurator/
│   │   │   │   │   ├── 📁 css/
│   │   │   │   │   │   └── 🎨 fontello.css
│   │   │   │   │   ├── 📁 font/
│   │   │   │   │   │   ├── 📜 LICENSE.txt
│   │   │   │   │   │   ├── 📄 config.json
│   │   │   │   │   │   ├── 📄 fontello.eot
│   │   │   │   │   │   ├── 🖼️ fontello.svg
│   │   │   │   │   │   ├── 📄 fontello.ttf
│   │   │   │   │   │   └── 📄 fontello.woff
│   │   │   │   │   ├── 📁 js/
│   │   │   │   │   │   ├── 📄 abstracttoolbarmodifier.js
│   │   │   │   │   │   ├── 📄 fulltoolbareditor.js
│   │   │   │   │   │   ├── 📄 toolbarmodifier.js
│   │   │   │   │   │   └── 📄 toolbartextmodifier.js
│   │   │   │   │   ├── 📁 lib/ 🚫 (auto-hidden)
│   │   │   │   │   └── 🌐 index.html
│   │   │   │   └── 🌐 index.html
│   │   │   ├── 📁 skins/
│   │   │   │   └── 📁 moono-lisa/
│   │   │   │       ├── 📁 images/
│   │   │   │       │   ├── 📁 hidpi/
│   │   │   │       │   │   ├── 🖼️ close.png
│   │   │   │       │   │   ├── 🖼️ lock-open.png
│   │   │   │       │   │   ├── 🖼️ lock.png
│   │   │   │       │   │   └── 🖼️ refresh.png
│   │   │   │       │   ├── 🖼️ arrow.png
│   │   │   │       │   ├── 🖼️ close.png
│   │   │   │       │   ├── 🖼️ lock-open.png
│   │   │   │       │   ├── 🖼️ lock.png
│   │   │   │       │   ├── 🖼️ refresh.png
│   │   │   │       │   └── 🖼️ spinner.gif
│   │   │   │       ├── 🎨 dialog.css
│   │   │   │       ├── 🎨 dialog_ie.css
│   │   │   │       ├── 🎨 dialog_ie8.css
│   │   │   │       ├── 🎨 dialog_iequirks.css
│   │   │   │       ├── 🎨 editor.css
│   │   │   │       ├── 🎨 editor_gecko.css
│   │   │   │       ├── 🎨 editor_ie.css
│   │   │   │       ├── 🎨 editor_ie8.css
│   │   │   │       ├── 🎨 editor_iequirks.css
│   │   │   │       ├── 🖼️ icons.png
│   │   │   │       ├── 🖼️ icons_hidpi.png
│   │   │   │       └── 📖 readme.md
│   │   │   ├── 📝 CHANGES.md
│   │   │   ├── 📜 LICENSE.md
│   │   │   ├── 📖 README.md
│   │   │   ├── 📝 SECURITY.md
│   │   │   ├── 📄 bender-runner.config.json
│   │   │   ├── 📄 build-config.js 🚫 (auto-hidden)
│   │   │   ├── 📄 ckeditor.js
│   │   │   ├── 📄 config.js
│   │   │   ├── 🎨 contents.css
│   │   │   ├── 🎨 custom.css
│   │   │   ├── 🎨 style.css
│   │   │   └── 📄 styles.js
│   │   ├── 📁 images/
│   │   │   ├── 🖼️ 404-illustration-dark.svg
│   │   │   ├── 🖼️ 404-illustration.svg
│   │   │   ├── 🖼️ LogoLavishStay.png
│   │   │   ├── 🖼️ auth-image.jpg
│   │   │   ├── 🖼️ auth1-image.jpg
│   │   │   ├── 🖼️ icon-01.svg
│   │   │   ├── 🖼️ icon-02.svg
│   │   │   ├── 🖼️ icon-03.svg
│   │   │   ├── 🖼️ user-36-05.jpg
│   │   │   ├── 🖼️ user-36-06.jpg
│   │   │   ├── 🖼️ user-36-07.jpg
│   │   │   ├── 🖼️ user-36-08.jpg
│   │   │   ├── 🖼️ user-36-09.jpg
│   │   │   └── 🖼️ user-avatar-32.png
│   │   ├── 📄 .htaccess
│   │   ├── 🖼️ favicon.ico
│   │   ├── 📄 hot 🚫 (auto-hidden)
│   │   ├── 🐘 index.php
│   │   ├── 📄 robots.txt
│   │   └── 📄 storage
│   ├── 📁 resources/
│   │   ├── 📁 css/
│   │   │   ├── 📁 additional-styles/
│   │   │   │   ├── 🎨 flatpickr.css
│   │   │   │   └── 🎨 utility-patterns.css
│   │   │   ├── 🎨 app.css
│   │   │   └── 🎨 custom.css
│   │   ├── 📁 js/
│   │   │   ├── 📁 components/
│   │   │   │   ├── 📄 dashboard-card-01 copy.js
│   │   │   │   ├── 📄 dashboard-card-01.js
│   │   │   │   ├── 📄 dashboard-card-02.js
│   │   │   │   ├── 📄 dashboard-card-03.js
│   │   │   │   ├── 📄 dashboard-card-04.js
│   │   │   │   ├── 📄 dashboard-card-05.js
│   │   │   │   ├── 📄 dashboard-card-06 copy.js
│   │   │   │   ├── 📄 dashboard-card-06.js
│   │   │   │   ├── 📄 dashboard-card-08.js
│   │   │   │   ├── 📄 dashboard-card-09.js
│   │   │   │   └── 📄 dashboard-card-11.js
│   │   │   ├── 📄 app.js
│   │   │   ├── 📄 bootstrap.js
│   │   │   └── 📄 utils.js
│   │   ├── 📁 markdown/
│   │   │   ├── 📝 policy.md
│   │   │   └── 📝 terms.md
│   │   └── 📁 views/
│   │       ├── 📁 admin/
│   │       │   ├── 📁 bookings/
│   │       │   │   ├── 📁 trading/
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   └── 🐘 index.blade.php
│   │       │   ├── 📁 components/
│   │       │   │   └── 🐘 pagination.blade.php
│   │       │   ├── 📁 dashboard/
│   │       │   │   ├── 🐘 analytics.blade.php
│   │       │   │   └── 🐘 dashboard.blade.php
│   │       │   ├── 📁 emails/
│   │       │   │   └── 🐘 reset-password.blade.php
│   │       │   ├── 📁 faqs/
│   │       │   │   ├── 🐘 create.blade.php
│   │       │   │   ├── 🐘 edit.blade.php
│   │       │   │   └── 🐘 index.blade.php
│   │       │   ├── 📁 floors/
│   │       │   │   ├── 🐘 create.blade.php
│   │       │   │   ├── 🐘 edit.blade.php
│   │       │   │   └── 🐘 index.blade.php
│   │       │   ├── 📁 multinational/
│   │       │   │   ├── 📁 currencies/
│   │       │   │   │   ├── 🐘 create.blade.php
│   │       │   │   │   ├── 🐘 edit.blade.php
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   └── 📁 languages/
│   │       │   │       ├── 🐘 create.blade.php
│   │       │   │       ├── 🐘 edit.blade.php
│   │       │   │       └── 🐘 index.blade.php
│   │       │   ├── 📁 news/
│   │       │   │   ├── 📁 categories/
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   ├── 🐘 create.blade.php
│   │       │   │   ├── 🐘 edit.blade.php
│   │       │   │   ├── 🐘 index.blade.php
│   │       │   │   └── 🐘 show.blade.php
│   │       │   ├── 📁 policy/
│   │       │   │   ├── 📁 cancellation-policies/
│   │       │   │   │   ├── 🐘 create.blade.php
│   │       │   │   │   ├── 🐘 edit.blade.php
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   ├── 📁 checkin-policies/
│   │       │   │   │   ├── 🐘 create.blade.php
│   │       │   │   │   ├── 🐘 edit.blade.php
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   ├── 📁 checkout-policies/
│   │       │   │   │   ├── 🐘 create.blade.php
│   │       │   │   │   ├── 🐘 edit.blade.php
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   ├── 📁 children-surcharge/
│   │       │   │   │   ├── 🐘 create.blade.php
│   │       │   │   │   ├── 🐘 edit.blade.php
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   ├── 📁 deposit-policies/
│   │       │   │   │   ├── 🐘 create.blade.php
│   │       │   │   │   ├── 🐘 edit.blade.php
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   ├── 📁 extend-policies/
│   │       │   │   │   ├── 🐘 create.blade.php
│   │       │   │   │   ├── 🐘 edit.blade.php
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   ├── 📁 reschedule-policies/
│   │       │   │   │   ├── 🐘 create.blade.php
│   │       │   │   │   ├── 🐘 edit.blade.php
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   └── 📁 transfer-policies/
│   │       │   │       ├── 🐘 create.blade.php
│   │       │   │       ├── 🐘 edit.blade.php
│   │       │   │       └── 🐘 index.blade.php
│   │       │   ├── 📁 pricing/
│   │       │   │   ├── 🐘 calculator.blade.php
│   │       │   │   ├── 🐘 config.blade.php
│   │       │   │   ├── 🐘 dynamic_price.blade.php
│   │       │   │   ├── 🐘 event_festival.blade.php
│   │       │   │   ├── 🐘 history.blade.php
│   │       │   │   └── 🐘 index.blade.php
│   │       │   ├── 📁 reviews/
│   │       │   │   └── 🐘 index.blade.php
│   │       │   ├── 📁 roles/
│   │       │   │   ├── 📁 permissions/
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   ├── 🐘 _permission_tree_alpine.blade.php
│   │       │   │   ├── 🐘 _permission_tree_edit.blade.php
│   │       │   │   ├── 🐘 create.blade.php
│   │       │   │   └── 🐘 index.blade.php
│   │       │   ├── 📁 room-types/
│   │       │   │   ├── 🐘 amenities.blade.php
│   │       │   │   ├── 🐘 create.blade.php
│   │       │   │   ├── 🐘 edit.blade.php
│   │       │   │   ├── 🐘 images.blade.php
│   │       │   │   ├── 🐘 index.blade.php
│   │       │   │   ├── 🐘 manage-package-services.blade.php
│   │       │   │   ├── 🐘 services.blade.php
│   │       │   │   └── 🐘 show.blade.php
│   │       │   ├── 📁 room_modification/
│   │       │   │   ├── 📁 booking_cancellations/
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   ├── 📁 booking_extensions/
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   ├── 📁 booking_reschedules/
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   ├── 📁 checkout_requests/
│   │       │   │   │   ├── 🐘 create.blade.php
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   └── 📁 room_transfers/
│   │       │   │       └── 🐘 index.blade.php
│   │       │   ├── 📁 rooms/
│   │       │   │   ├── 🐘 create.blade.php
│   │       │   │   ├── 🐘 edit.blade.php
│   │       │   │   ├── 🐘 import-preview.blade.php
│   │       │   │   ├── 🐘 index.blade.php
│   │       │   │   ├── 🐘 rooms.blade.php
│   │       │   │   └── 🐘 show.blade.php
│   │       │   ├── 📁 services/
│   │       │   │   ├── 📁 amenities/
│   │       │   │   │   ├── 🐘 create.blade.php
│   │       │   │   │   ├── 🐘 edit.blade.php
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   ├── 📁 beds/
│   │       │   │   │   ├── 🐘 create.blade.php
│   │       │   │   │   ├── 🐘 edit.blade.php
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   ├── 📁 meals/
│   │       │   │   │   ├── 🐘 create.blade.php
│   │       │   │   │   ├── 🐘 edit.blade.php
│   │       │   │   │   └── 🐘 index.blade.php
│   │       │   │   └── 📁 services/
│   │       │   │       ├── 🐘 create.blade.php
│   │       │   │       ├── 🐘 edit.blade.php
│   │       │   │       └── 🐘 index.blade.php
│   │       │   ├── 📁 translation/
│   │       │   │   ├── 🐘 edit.blade.php
│   │       │   │   ├── 🐘 index.blade.php
│   │       │   │   ├── 🐘 manage-tables.blade.php
│   │       │   │   └── 🐘 show.blade.php
│   │       │   ├── 📁 users/
│   │       │   │   ├── 📁 customers/
│   │       │   │   │   ├── 🐘 create.blade.php
│   │       │   │   │   ├── 🐘 edit.blade.php
│   │       │   │   │   ├── 🐘 index.blade.php
│   │       │   │   │   └── 🐘 show.blade.php
│   │       │   │   └── 📁 staffs/
│   │       │   │       ├── 🐘 create.blade.php
│   │       │   │       ├── 🐘 edit.blade.php
│   │       │   │       ├── 🐘 index.blade.php
│   │       │   │       └── 🐘 show.blade.php
│   │       │   └── 🐘 chat-support.blade.php
│   │       ├── 📁 api/
│   │       │   ├── 🐘 api-token-manager.blade.php
│   │       │   └── 🐘 index.blade.php
│   │       ├── 📁 auth/
│   │       │   ├── 🐘 confirm-password.blade.php
│   │       │   ├── 🐘 forgot-password.blade.php
│   │       │   ├── 🐘 login.blade.php
│   │       │   ├── 🐘 register.blade.php
│   │       │   ├── 🐘 reset-password.blade.php
│   │       │   ├── 🐘 two-factor-challenge.blade.php
│   │       │   └── 🐘 verify-email.blade.php
│   │       ├── 📁 components/
│   │       │   ├── 📁 app/
│   │       │   │   ├── 🐘 header.blade.php
│   │       │   │   ├── 🐘 preloader.blade.php
│   │       │   │   └── 🐘 sidebar.blade.php
│   │       │   ├── 📁 dashboard/
│   │       │   │   ├── 🐘 alert-card.blade.php
│   │       │   │   ├── 🐘 dashboard-card-01.blade.php
│   │       │   │   ├── 🐘 dashboard-card-02.blade.php
│   │       │   │   ├── 🐘 dashboard-card-03.blade.php
│   │       │   │   ├── 🐘 dashboard-card-04.blade.php
│   │       │   │   ├── 🐘 dashboard-card-05.blade.php
│   │       │   │   ├── 🐘 dashboard-card-06.blade.php
│   │       │   │   ├── 🐘 dashboard-card-07.blade.php
│   │       │   │   ├── 🐘 dashboard-card-08.blade.php
│   │       │   │   ├── 🐘 dashboard-card-09.blade.php
│   │       │   │   ├── 🐘 dashboard-card-10.blade.php
│   │       │   │   ├── 🐘 dashboard-card-11.blade.php
│   │       │   │   ├── 🐘 dashboard-card-12.blade.php
│   │       │   │   └── 🐘 dashboard-card-13.blade.php
│   │       │   ├── 🐘 action-message.blade.php
│   │       │   ├── 🐘 action-section.blade.php
│   │       │   ├── 🐘 button.blade.php
│   │       │   ├── 🐘 confirmation-modal.blade.php
│   │       │   ├── 🐘 confirms-password.blade.php
│   │       │   ├── 🐘 danger-button.blade.php
│   │       │   ├── 🐘 date-select.blade.php
│   │       │   ├── 🐘 datepicker.blade.php
│   │       │   ├── 🐘 dialog-modal.blade.php
│   │       │   ├── 🐘 dropdown-filter.blade.php
│   │       │   ├── 🐘 dropdown-help.blade.php
│   │       │   ├── 🐘 dropdown-link.blade.php
│   │       │   ├── 🐘 dropdown-notifications.blade.php
│   │       │   ├── 🐘 dropdown-profile.blade.php
│   │       │   ├── 🐘 dropdown.blade.php
│   │       │   ├── 🐘 form-section.blade.php
│   │       │   ├── 🐘 input-error.blade.php
│   │       │   ├── 🐘 input.blade.php
│   │       │   ├── 🐘 label.blade.php
│   │       │   ├── 🐘 modal-search.blade.php
│   │       │   ├── 🐘 modal.blade.php
│   │       │   ├── 🐘 nav-link.blade.php
│   │       │   ├── 🐘 pagination-classic.blade.php
│   │       │   ├── 🐘 pagination-numeric.blade.php
│   │       │   ├── 🐘 responsive-nav-link.blade.php
│   │       │   ├── 🐘 room-calendar-modal.blade.php
│   │       │   ├── 🐘 search-form.blade.php
│   │       │   ├── 🐘 secondary-button.blade.php
│   │       │   ├── 🐘 section-border.blade.php
│   │       │   ├── 🐘 section-title.blade.php
│   │       │   ├── 🐘 switchable-team.blade.php
│   │       │   ├── 🐘 theme-toggle.blade.php
│   │       │   └── 🐘 validation-errors.blade.php
│   │       ├── 📁 emails/
│   │       │   └── 🐘 booking-confirmation.blade.php
│   │       ├── 📁 layouts/
│   │       │   ├── 🐘 app.blade.php
│   │       │   ├── 🐘 authentication.blade.php
│   │       │   └── 🐘 guest.blade.php
│   │       ├── 📁 pages/
│   │       │   ├── 📁 dashboard/
│   │       │   │   └── 🐘 dashboard.blade.php
│   │       │   └── 📁 utility/
│   │       │       └── 🐘 404.blade.php
│   │       ├── 📁 payment/
│   │       │   └── 🐘 test-form.blade.php
│   │       ├── 📁 profile/
│   │       │   ├── 🐘 delete-user-form.blade.php
│   │       │   ├── 🐘 logout-other-browser-sessions-form.blade.php
│   │       │   ├── 🐘 show.blade.php
│   │       │   ├── 🐘 two-factor-authentication-form.blade.php
│   │       │   ├── 🐘 update-password-form.blade.php
│   │       │   └── 🐘 update-profile-information-form.blade.php
│   │       ├── 🐘 policy.blade.php
│   │       └── 🐘 terms.blade.php
│   ├── 📁 routes/
│   │   ├── 🐘 api.php
│   │   ├── 🐘 channels.php
│   │   ├── 🐘 console.php
│   │   └── 🐘 web.php
│   ├── 📁 storage/
│   │   ├── 📁 app/
│   │   │   └── 📁 public/
│   │   │       ├── 📁 images/
│   │   │       │   ├── 📁 about/
│   │   │       │   │   └── 📄 banner.avif
│   │   │       │   ├── 📁 city/
│   │   │       │   │   ├── 🖼️ binh-dinh.webp
│   │   │       │   │   ├── 🖼️ da-lat.webp
│   │   │       │   │   ├── 🖼️ da-nang.webp
│   │   │       │   │   ├── 🖼️ ha-long.webp
│   │   │       │   │   ├── 🖼️ ha-noi.webp
│   │   │       │   │   ├── 🖼️ ho-chi-minh.webp
│   │   │       │   │   ├── 🖼️ hoi-an.webp
│   │   │       │   │   ├── 🖼️ nha-trang.webp
│   │   │       │   │   ├── 🖼️ phan-thiet.webp
│   │   │       │   │   ├── 🖼️ phu-quoc.webp
│   │   │       │   │   ├── 🖼️ sa-pa.webp
│   │   │       │   │   └── 🖼️ vung-tau.webp
│   │   │       │   ├── 📁 home/
│   │   │       │   │   ├── 🖼️ Newsletter.png
│   │   │       │   │   ├── 📄 beboi.avif
│   │   │       │   │   ├── 📄 gym.avif
│   │   │       │   │   ├── 📄 luxury-hotel-room-banner.avif
│   │   │       │   │   ├── 📄 melia-banner.avif
│   │   │       │   │   └── 📄 spa.avif
│   │   │       │   ├── 📁 hotels/
│   │   │       │   │   ├── 🖼️ 1.jpg
│   │   │       │   │   ├── 🖼️ 10.jpg
│   │   │       │   │   ├── 🖼️ 11.jpg
│   │   │       │   │   ├── 🖼️ 12.jpg
│   │   │       │   │   ├── 🖼️ 13.jpg
│   │   │       │   │   ├── 🖼️ 14.jpg
│   │   │       │   │   ├── 🖼️ 15.jpg
│   │   │       │   │   ├── 🖼️ 16.jpg
│   │   │       │   │   ├── 🖼️ 17.jpg
│   │   │       │   │   ├── 🖼️ 18.jpg
│   │   │       │   │   ├── 🖼️ 2.jpg
│   │   │       │   │   ├── 🖼️ 3.jpg
│   │   │       │   │   ├── 🖼️ 4.jpg
│   │   │       │   │   ├── 🖼️ 5.jpg
│   │   │       │   │   ├── 🖼️ 6.jpg
│   │   │       │   │   ├── 🖼️ 7.jpg
│   │   │       │   │   ├── 🖼️ 8.jpg
│   │   │       │   │   └── 🖼️ 9.jpg
│   │   │       │   ├── 📁 users/
│   │   │       │   │   ├── 🖼️ 1.jpg
│   │   │       │   │   ├── 🖼️ 10.jpg
│   │   │       │   │   ├── 🖼️ 11.jpg
│   │   │       │   │   ├── 🖼️ 12.jpg
│   │   │       │   │   ├── 🖼️ 2.jpg
│   │   │       │   │   ├── 🖼️ 3.jpg
│   │   │       │   │   ├── 🖼️ 4.jpg
│   │   │       │   │   ├── 🖼️ 5.jpg
│   │   │       │   │   ├── 🖼️ 6.jpg
│   │   │       │   │   ├── 🖼️ 7.jpg
│   │   │       │   │   ├── 🖼️ 8.jpg
│   │   │       │   │   ├── 🖼️ 9.jpg
│   │   │       │   │   └── 🖼️ default.png
│   │   │       │   ├── 🖼️ favicon.ico
│   │   │       │   ├── 🖼️ faviconV2.png
│   │   │       │   ├── 🖼️ logo.png
│   │   │       │   └── 🖼️ luxury-hotel-room-banner.jpg
│   │   │       ├── 📁 media/
│   │   │       │   ├── 🖼️ 1753807880_screenshot-1png.png
│   │   │       │   ├── 🖼️ 1753807896_logopng.png
│   │   │       │   ├── 🖼️ 1753852598_logopng.png
│   │   │       │   ├── 🖼️ 1754039682_screenshot-1png.png
│   │   │       │   ├── 🖼️ 1754040487_faviconico.ico
│   │   │       │   └── 🖼️ 1754040720_faviconico.ico
│   │   │       ├── 📁 profile-photos/
│   │   │       │   ├── 🖼️ HkHH1opjbMUOUFbd9DPPimJKVRodA9lwkuKMCHmJ.jpg
│   │   │       │   ├── 🖼️ LZSncWQc6YYUEaPzOLdd2cx72Bp0PjTR2CF9NvJs.png
│   │   │       │   └── 🖼️ mfqMmmx1jtzkRy9YdNHQRl7xjSLZwxGgqDHJd4JS.png
│   │   │       └── 📁 room-types/
│   │   │           ├── 📁 1/
│   │   │           │   ├── 🖼️ 1.jpg
│   │   │           │   ├── 🖼️ 2.jpg
│   │   │           │   ├── 🖼️ 3.webp
│   │   │           │   ├── 🖼️ 4 - Copy.webp
│   │   │           │   └── 🖼️ 4.webp
│   │   │           ├── 📁 2/
│   │   │           │   ├── 🖼️ 1.jpg
│   │   │           │   ├── 🖼️ 2.jpg
│   │   │           │   ├── 🖼️ 3.webp
│   │   │           │   ├── 🖼️ 4.jpg
│   │   │           │   ├── 🖼️ 5.webp
│   │   │           │   └── 🖼️ 6.webp
│   │   │           ├── 📁 3/
│   │   │           │   ├── 🖼️ 1.jpg
│   │   │           │   ├── 🖼️ 2.webp
│   │   │           │   ├── 🖼️ 3.jpg
│   │   │           │   ├── 🖼️ 4.jpg
│   │   │           │   ├── 🖼️ 5.jpg
│   │   │           │   ├── 🖼️ 6.webp
│   │   │           │   ├── 🖼️ 7.jpg
│   │   │           │   └── 🖼️ 8.webp
│   │   │           ├── 📁 4/
│   │   │           │   ├── 🖼️ 1.webp
│   │   │           │   ├── 🖼️ 2.jpg
│   │   │           │   ├── 🖼️ 3.webp
│   │   │           │   ├── 🖼️ 4.jpg
│   │   │           │   ├── 🖼️ 5.webp
│   │   │           │   └── 🖼️ 6.jpg
│   │   │           ├── 📁 5/
│   │   │           │   ├── 🖼️ 1.webp
│   │   │           │   ├── 🖼️ 2.webp
│   │   │           │   ├── 🖼️ 3.webp
│   │   │           │   ├── 🖼️ 4.jpg
│   │   │           │   ├── 🖼️ 5.jpg
│   │   │           │   ├── 🖼️ 6.jpg
│   │   │           │   ├── 🖼️ 7.webp
│   │   │           │   └── 🖼️ 8.jpg
│   │   │           ├── 📁 6/
│   │   │           │   ├── 🖼️ 1.webp
│   │   │           │   ├── 🖼️ 2.webp
│   │   │           │   ├── 🖼️ 3.webp
│   │   │           │   ├── 🖼️ 4.webp
│   │   │           │   ├── 🖼️ 5.jpg
│   │   │           │   ├── 🖼️ 6.jpg
│   │   │           │   └── 🖼️ 7.webp
│   │   │           └── 📁 7/
│   │   │               ├── 🖼️ 1.webp
│   │   │               ├── 🖼️ 10.webp
│   │   │               ├── 🖼️ 11.jpg
│   │   │               ├── 🖼️ 12.webp
│   │   │               ├── 🖼️ 13.jpg
│   │   │               ├── 🖼️ 14.webp
│   │   │               ├── 🖼️ 15.jpg
│   │   │               ├── 🖼️ 16.jpg
│   │   │               ├── 🖼️ 17.jpg
│   │   │               ├── 🖼️ 18.jpg
│   │   │               ├── 🖼️ 2.jpg
│   │   │               ├── 🖼️ 3.jpg
│   │   │               ├── 🖼️ 4.jpg
│   │   │               ├── 🖼️ 5.jpg
│   │   │               ├── 🖼️ 6.jpg
│   │   │               ├── 🖼️ 7.jpg
│   │   │               ├── 🖼️ 8.jpg
│   │   │               └── 🖼️ 9.jpg
│   │   ├── 📁 framework/
│   │   │   ├── 📁 cache/ 🚫 (auto-hidden)
│   │   │   ├── 📁 sessions/
│   │   │   │   ├── 🚫 .gitignore
│   │   │   │   └── 📄 i3IpvLvLsz30txEbSywA5fbYF0Zw8h7AawQmLigV
│   │   │   ├── 📁 testing/
│   │   │   │   └── 🚫 .gitignore
│   │   │   ├── 📁 views/
│   │   │   │   ├── 🚫 .gitignore
│   │   │   │   ├── 🐘 07b757fbc25494a3a81188bd801c4ad2.php
│   │   │   │   ├── 🐘 08417ecdfdfc02cd326dd112300edc06.php
│   │   │   │   ├── 🐘 08efe1c1de6228bcfd83259eb96daf7f.php
│   │   │   │   ├── 🐘 0b939cc4a2da669faa6057aff5f63602.php
│   │   │   │   ├── 🐘 13dd7e5fe5b5cbdd825995d3766258fa.php
│   │   │   │   ├── 🐘 17f03e59ee16d76fb17a09fd854a2b16.php
│   │   │   │   ├── 🐘 20e9af14e435531db902142f6142cd83.php
│   │   │   │   ├── 🐘 2517c5bf649427bce413423d0187b6bf.php
│   │   │   │   ├── 🐘 2a419115f0e74ae1631b136b10bcb31e.php
│   │   │   │   ├── 🐘 2bafe32347d3fc8d39c4d8352d005868.php
│   │   │   │   ├── 🐘 2cdf496ae50e2aa2c8ee567d029b1da3.php
│   │   │   │   ├── 🐘 33a7a71a68d308d0912357e96664ffd4.php
│   │   │   │   ├── 🐘 3b857867acfbe29abc6aeb5c3f88e38c.php
│   │   │   │   ├── 🐘 3e4d81ce4dec4adb393db8d049626cdc.php
│   │   │   │   ├── 🐘 455efe7cb9a4febcc7cf256029430163.php
│   │   │   │   ├── 🐘 496bec926f1bbc07587e57e47e4e119f.php
│   │   │   │   ├── 🐘 51b00d3b427fb77b554db2f3ea685e4a.php
│   │   │   │   ├── 🐘 52a72947c78ba6804066b8530f389ca8.php
│   │   │   │   ├── 🐘 551f9503adc0ec9b103448c4d96e7504.php
│   │   │   │   ├── 🐘 574eff020888bd15170093d6f69573ab.php
│   │   │   │   ├── 🐘 587a63e0ac90c1d22d962c02560ed7a3.php
│   │   │   │   ├── 🐘 58956750c0f6476066b8751f4eb3205d.php
│   │   │   │   ├── 🐘 5bf9114fbdeb3b1d292cc39abe6cbf9a.php
│   │   │   │   ├── 🐘 6191054224e8a3d144a80a7f7de7264e.php
│   │   │   │   ├── 🐘 61fbb306e79fea0457bb873dfc712aab.php
│   │   │   │   ├── 🐘 63a8de64279ab9f623f74c2c0179379f.php
│   │   │   │   ├── 🐘 6416539c76c6f17a07bd601cbbba7bca.php
│   │   │   │   ├── 🐘 76c3792361af645370f44193b4fb9d17.php
│   │   │   │   ├── 🐘 7bf4e40826365598c8e9a57319b3376a.php
│   │   │   │   ├── 🐘 88ed60ea1b802dd62177b281eb8ce485.php
│   │   │   │   ├── 🐘 8a8d5b948a7d7912c769c7be753d80d9.php
│   │   │   │   ├── 🐘 8cfe57f0289824bbd4001cbbfdec1383.php
│   │   │   │   ├── 🐘 908b6796c17726fe9824839ebec517c1.php
│   │   │   │   ├── 🐘 93dc61e054af86b23a84608a3faa8f62.php
│   │   │   │   ├── 🐘 9471d22878d4890474a93f2df738807e.php
│   │   │   │   ├── 🐘 95a3e70818e1d3fd4c43d68a0ced6202.php
│   │   │   │   ├── 🐘 97d235cab35e4912bc28e7f2467e5846.php
│   │   │   │   ├── 🐘 9a5aa6cf087b3c1358f7154a5e2cfa2d.php
│   │   │   │   ├── 🐘 a08d62939d9c00af4b84175aa5bf3958.php
│   │   │   │   ├── 🐘 a50615ea82742e8298d52210c7e9deab.php
│   │   │   │   ├── 🐘 a6b868c632ec392f818d8bcb255af3bc.php
│   │   │   │   ├── 🐘 a77063f163840bb1e557223d7da8ab40.php
│   │   │   │   ├── 🐘 a7c34ba3a08eecebcbc249cffda5d8d7.php
│   │   │   │   ├── 🐘 a900208245f7942c6086437608b7b05a.php
│   │   │   │   ├── 🐘 ab7327cb40a34e30b3a230a646f5c989.php
│   │   │   │   ├── 🐘 ab9054af7800ca22d2fd81445cdeeaf9.php
│   │   │   │   ├── 🐘 ac90a776582bb6b8cb8c0045af073aae.php
│   │   │   │   ├── 🐘 af4897a7cba123c3073026364203f712.php
│   │   │   │   ├── 🐘 b50c398e7545883b7bdc571160efa1b7.php
│   │   │   │   ├── 🐘 b95f3a27f46f4e51ebd97d2e9a3c7e9e.php
│   │   │   │   ├── 🐘 bc64bcc27d08a906c277af04866843ed.php
│   │   │   │   ├── 🐘 bd88f87e5b8e7dc774b00938f91e81eb.php
│   │   │   │   ├── 🐘 c4ce7cabe7588650dbbeaf2c930b48a5.php
│   │   │   │   ├── 🐘 cba2087647f9cbe4630d15b08e63fbda.php
│   │   │   │   ├── 🐘 d66e2644676c4b1a5f3c96b9d4254fde.php
│   │   │   │   ├── 🐘 d9ccb28dfbf0049dd4426b23fca4b329.php
│   │   │   │   ├── 🐘 dad0d190ee1244e6784f57b90c18ce18.php
│   │   │   │   ├── 🐘 dae18936e9d3dfad3a25bbbea94363e8.php
│   │   │   │   ├── 🐘 db2cffa30242dd8843215d0db793d2dc.php
│   │   │   │   ├── 🐘 e6a6aa6f4bdc56d4193d19fbc69aef1f.php
│   │   │   │   ├── 🐘 ee535d34c592650b179b021ca8efd1da.php
│   │   │   │   ├── 🐘 f5a71eee2ac05a211b75dbd0fb0bb6a3.php
│   │   │   │   ├── 🐘 f8355bba901a024e1b45d88f70ad74d2.php
│   │   │   │   ├── 🐘 f983bb92a4a471074af55a13a634c2be.php
│   │   │   │   ├── 🐘 fb7fbcef2b3be7648ff1c2963dd20ad9.php
│   │   │   │   └── 🐘 fcee2a80d305c557c71ccf20faeb763c.php
│   │   │   └── 🚫 .gitignore
│   │   └── 📁 logs/
│   │       ├── 🚫 .gitignore
│   │       └── 📋 laravel.log 🚫 (auto-hidden)
│   ├── 📁 tests/
│   │   ├── 📁 Feature/
│   │   │   ├── 🐘 ApiTokenPermissionsTest.php
│   │   │   ├── 🐘 AuthenticationTest.php
│   │   │   ├── 🐘 BrowserSessionsTest.php
│   │   │   ├── 🐘 CreateApiTokenTest.php
│   │   │   ├── 🐘 DeleteAccountTest.php
│   │   │   ├── 🐘 DeleteApiTokenTest.php
│   │   │   ├── 🐘 EmailVerificationTest.php
│   │   │   ├── 🐘 ExampleTest.php
│   │   │   ├── 🐘 GoogleOAuthTest.php
│   │   │   ├── 🐘 NewsApiTest.php
│   │   │   ├── 🐘 PasswordConfirmationTest.php
│   │   │   ├── 🐘 PasswordResetTest.php
│   │   │   ├── 🐘 ProfileInformationTest.php
│   │   │   ├── 🐘 RegistrationTest.php
│   │   │   ├── 🐘 TwoFactorAuthenticationSettingsTest.php
│   │   │   └── 🐘 UpdatePasswordTest.php
│   │   ├── 📁 Unit/
│   │   │   └── 🐘 ExampleTest.php
│   │   ├── 🐘 CreatesApplication.php
│   │   └── 🐘 TestCase.php
│   ├── 📁 vendor/ 🚫 (auto-hidden)
│   ├── 📁 vnpay_php/
│   │   ├── 📁 assets/
│   │   │   ├── 🎨 bootstrap.min.css 🚫 (auto-hidden)
│   │   │   ├── 📄 jquery-1.11.3.min.js 🚫 (auto-hidden)
│   │   │   └── 🎨 jumbotron-narrow.css
│   │   ├── 🐘 config.php
│   │   ├── 🐘 index.php
│   │   ├── 🐘 vnpay_create_payment.php
│   │   ├── 🐘 vnpay_ipn.php
│   │   ├── 🐘 vnpay_pay.php
│   │   ├── 🐘 vnpay_querydr.php
│   │   ├── 🐘 vnpay_refund.php
│   │   └── 🐘 vnpay_return.php
│   ├── 📄 .editorconfig
│   ├── 🔒 .env 🚫 (auto-hidden)
│   ├── 🗑️ .phpunit.result.cache 🚫 (auto-hidden)
│   ├── 📝 CHANGELOG.md
│   ├── 📄 LavishStay_News_API.postman_collection.json
│   ├── 📝 NEWS_API_DOCUMENTATION.md
│   ├── 📖 README.md
│   ├── 📄 artisan
│   ├── 📄 composer.json
│   ├── 🔒 composer.lock 🚫 (auto-hidden)
│   ├── 📄 package-lock.json
│   ├── 📄 package.json
│   ├── 📄 phpunit.xml
│   ├── ⚙️ pnpm-lock.yaml
│   ├── 📄 postcss.config.js
│   ├── 📄 temp_response.json
│   ├── 🐘 test-google-client.php
│   ├── 🐘 update_room_images.php
│   └── 📄 vite.config.js
├── 📁 lavishstay-frontend/
│   ├── 📁 .vite/
│   │   └── 📁 deps/
│   │       ├── 📄 _metadata.json
│   │       └── 📄 package.json
│   ├── 📁 dist/ 🚫 (auto-hidden)
│   ├── 📁 node_modules/ 🚫 (auto-hidden)
│   ├── 📁 public/
│   │   ├── 📁 images/
│   │   │   ├── 📁 about/
│   │   │   │   └── 📄 banner.avif
│   │   │   ├── 📁 city/
│   │   │   │   ├── 🖼️ binh-dinh.webp
│   │   │   │   ├── 🖼️ da-lat.webp
│   │   │   │   ├── 🖼️ da-nang.webp
│   │   │   │   ├── 🖼️ ha-long.webp
│   │   │   │   ├── 🖼️ ha-noi.webp
│   │   │   │   ├── 🖼️ ho-chi-minh.webp
│   │   │   │   ├── 🖼️ hoi-an.webp
│   │   │   │   ├── 🖼️ nha-trang.webp
│   │   │   │   ├── 🖼️ phan-thiet.webp
│   │   │   │   ├── 🖼️ phu-quoc.webp
│   │   │   │   ├── 🖼️ sa-pa.webp
│   │   │   │   └── 🖼️ vung-tau.webp
│   │   │   ├── 📁 home/
│   │   │   │   ├── 🖼️ Newsletter.png
│   │   │   │   ├── 📄 beboi.avif
│   │   │   │   ├── 📄 gym.avif
│   │   │   │   ├── 📄 luxury-hotel-room-banner.avif
│   │   │   │   ├── 📄 melia-banner.avif
│   │   │   │   └── 📄 spa.avif
│   │   │   ├── 📁 hotels/
│   │   │   │   ├── 🖼️ 1.jpg
│   │   │   │   ├── 🖼️ 10.jpg
│   │   │   │   ├── 🖼️ 11.jpg
│   │   │   │   ├── 🖼️ 12.jpg
│   │   │   │   ├── 🖼️ 13.jpg
│   │   │   │   ├── 🖼️ 14.jpg
│   │   │   │   ├── 🖼️ 15.jpg
│   │   │   │   ├── 🖼️ 16.jpg
│   │   │   │   ├── 🖼️ 17.jpg
│   │   │   │   ├── 🖼️ 18.jpg
│   │   │   │   ├── 🖼️ 2.jpg
│   │   │   │   ├── 🖼️ 3.jpg
│   │   │   │   ├── 🖼️ 4.jpg
│   │   │   │   ├── 🖼️ 5.jpg
│   │   │   │   ├── 🖼️ 6.jpg
│   │   │   │   ├── 🖼️ 7.jpg
│   │   │   │   ├── 🖼️ 8.jpg
│   │   │   │   └── 🖼️ 9.jpg
│   │   │   ├── 📁 room/
│   │   │   │   ├── 📁 Deluxe_Room/
│   │   │   │   │   ├── 🖼️ 1.jpg
│   │   │   │   │   ├── 🖼️ 2.jpg
│   │   │   │   │   ├── 🖼️ 3.webp
│   │   │   │   │   ├── 🖼️ 4 - Copy.webp
│   │   │   │   │   └── 🖼️ 4.webp
│   │   │   │   ├── 📁 Premium_Corner_Room/
│   │   │   │   │   ├── 🖼️ 1.jpg
│   │   │   │   │   ├── 🖼️ 2.jpg
│   │   │   │   │   ├── 🖼️ 3.webp
│   │   │   │   │   ├── 🖼️ 4.jpg
│   │   │   │   │   ├── 🖼️ 5.webp
│   │   │   │   │   └── 🖼️ 6.webp
│   │   │   │   ├── 📁 Presidential_Suite/
│   │   │   │   │   ├── 🖼️ 1.webp
│   │   │   │   │   ├── 🖼️ 10.webp
│   │   │   │   │   ├── 🖼️ 11.jpg
│   │   │   │   │   ├── 🖼️ 12.webp
│   │   │   │   │   ├── 🖼️ 13.jpg
│   │   │   │   │   ├── 🖼️ 14.webp
│   │   │   │   │   ├── 🖼️ 15.jpg
│   │   │   │   │   ├── 🖼️ 16.jpg
│   │   │   │   │   ├── 🖼️ 17.jpg
│   │   │   │   │   ├── 🖼️ 18.jpg
│   │   │   │   │   ├── 🖼️ 2.jpg
│   │   │   │   │   ├── 🖼️ 3.jpg
│   │   │   │   │   ├── 🖼️ 4.jpg
│   │   │   │   │   ├── 🖼️ 5.jpg
│   │   │   │   │   ├── 🖼️ 6.jpg
│   │   │   │   │   ├── 🖼️ 7.jpg
│   │   │   │   │   ├── 🖼️ 8.jpg
│   │   │   │   │   └── 🖼️ 9.jpg
│   │   │   │   ├── 📁 Suite/
│   │   │   │   │   ├── 🖼️ 1.webp
│   │   │   │   │   ├── 🖼️ 2.webp
│   │   │   │   │   ├── 🖼️ 3.webp
│   │   │   │   │   ├── 🖼️ 4.webp
│   │   │   │   │   ├── 🖼️ 5.jpg
│   │   │   │   │   ├── 🖼️ 6.jpg
│   │   │   │   │   └── 🖼️ 7.webp
│   │   │   │   ├── 📁 The_Level_Premium_Corner_Room/
│   │   │   │   │   ├── 🖼️ 1.webp
│   │   │   │   │   ├── 🖼️ 2.jpg
│   │   │   │   │   ├── 🖼️ 3.webp
│   │   │   │   │   ├── 🖼️ 4.jpg
│   │   │   │   │   ├── 🖼️ 5.webp
│   │   │   │   │   └── 🖼️ 6.jpg
│   │   │   │   ├── 📁 The_Level_Premium_Room/
│   │   │   │   │   ├── 🖼️ 1.jpg
│   │   │   │   │   ├── 🖼️ 2.webp
│   │   │   │   │   ├── 🖼️ 3.jpg
│   │   │   │   │   ├── 🖼️ 4.jpg
│   │   │   │   │   ├── 🖼️ 5.jpg
│   │   │   │   │   ├── 🖼️ 6.webp
│   │   │   │   │   ├── 🖼️ 7.jpg
│   │   │   │   │   └── 🖼️ 8.webp
│   │   │   │   └── 📁 The_Level_Suite_Room/
│   │   │   │       ├── 🖼️ 1.webp
│   │   │   │       ├── 🖼️ 2.webp
│   │   │   │       ├── 🖼️ 3.webp
│   │   │   │       ├── 🖼️ 4.jpg
│   │   │   │       ├── 🖼️ 5.jpg
│   │   │   │       ├── 🖼️ 6.jpg
│   │   │   │       ├── 🖼️ 7.webp
│   │   │   │       └── 🖼️ 8.jpg
│   │   │   ├── 📁 users/
│   │   │   │   ├── 🖼️ 1.jpg
│   │   │   │   ├── 🖼️ 10.jpg
│   │   │   │   ├── 🖼️ 11.jpg
│   │   │   │   ├── 🖼️ 12.jpg
│   │   │   │   ├── 🖼️ 2.jpg
│   │   │   │   ├── 🖼️ 3.jpg
│   │   │   │   ├── 🖼️ 4.jpg
│   │   │   │   ├── 🖼️ 5.jpg
│   │   │   │   ├── 🖼️ 6.jpg
│   │   │   │   ├── 🖼️ 7.jpg
│   │   │   │   ├── 🖼️ 8.jpg
│   │   │   │   ├── 🖼️ 9.jpg
│   │   │   │   └── 🖼️ default.png
│   │   │   ├── 🖼️ chatbot-img.png
│   │   │   ├── 🖼️ favicon.ico
│   │   │   ├── 🖼️ faviconV2.png
│   │   │   ├── 🖼️ logo.png
│   │   │   └── 🖼️ luxury-hotel-room-banner.jpg
│   │   ├── 🖼️ favicon.ico
│   │   ├── 🌐 index.html
│   │   ├── 🖼️ lavishstay-login-illustration.svg
│   │   ├── 🖼️ logo192.png
│   │   ├── 🖼️ logo512.png
│   │   ├── 📄 manifest.json
│   │   └── 📄 robots.txt
│   ├── 📁 src/
│   │   ├── 📁 api/
│   │   │   ├── 📄 reception.api.ts
│   │   │   └── 📄 roomDetailApi.ts
│   │   ├── 📁 assets/
│   │   │   └── 📁 images/
│   │   │       ├── 🖼️ en-flag.svg
│   │   │       ├── 🖼️ illustration.svg
│   │   │       ├── 🖼️ logo-dark.png
│   │   │       ├── 🖼️ logo-light.png
│   │   │       └── 🖼️ vi-flag.svg
│   │   ├── 📁 components/
│   │   │   ├── 📁 Cart/
│   │   │   │   └── 📄 CartWidget.tsx
│   │   │   ├── 📁 auth/
│   │   │   │   ├── 📄 AuthModal.tsx
│   │   │   │   ├── 📄 GoogleLoginButton.tsx
│   │   │   │   ├── 📄 GoogleLoginRedirect.tsx
│   │   │   │   ├── 📄 GoogleOAuthCallback.tsx
│   │   │   │   ├── 📄 GoogleOAuthSetupGuide.tsx
│   │   │   │   ├── 📄 GoogleOAuthTest.tsx
│   │   │   │   ├── 📄 LoginForm.tsx
│   │   │   │   └── 📄 RegisterForm.tsx
│   │   │   ├── 📁 booking/
│   │   │   │   └── 📄 ValidationSummary.tsx
│   │   │   ├── 📁 booking-management/
│   │   │   │   ├── 📄 BookingFilterBar.tsx
│   │   │   │   └── 📄 index.tsx
│   │   │   ├── 📁 common/
│   │   │   │   ├── 📄 AmenityDisplay.tsx
│   │   │   │   └── 📄 ErrorBoundary.tsx
│   │   │   ├── 📁 demo/
│   │   │   │   └── 📄 RoomDetailDemo.tsx
│   │   │   ├── 📁 layouts/
│   │   │   │   ├── 📄 AuthLayout.tsx
│   │   │   │   ├── 📄 DashboardLayout.tsx
│   │   │   │   ├── 📄 DefaultLayout.tsx
│   │   │   │   ├── 📄 Footer.tsx
│   │   │   │   ├── 📄 Header.tsx
│   │   │   │   ├── 📄 MainLayout.tsx
│   │   │   │   └── 📄 index.ts
│   │   │   ├── 📁 news/
│   │   │   │   ├── 📄 CommentForm.tsx
│   │   │   │   ├── 📄 CommentList.tsx
│   │   │   │   ├── 📄 NewsBookmarkButton.tsx
│   │   │   │   ├── 📄 NewsCard.tsx
│   │   │   │   ├── 📄 NewsCategoryFilter.tsx
│   │   │   │   ├── 📄 NewsCategoryTabs.tsx
│   │   │   │   ├── 📄 NewsDetail.tsx
│   │   │   │   ├── 📄 NewsFooter.tsx
│   │   │   │   ├── 📄 NewsHeader.tsx
│   │   │   │   ├── 📄 NewsHighlights.tsx
│   │   │   │   ├── 📄 NewsItem.tsx
│   │   │   │   ├── 📄 NewsLikeButton.tsx
│   │   │   │   ├── 📄 NewsList.tsx
│   │   │   │   ├── 📄 NewsMainHighlight.tsx
│   │   │   │   ├── 📄 NewsModal.tsx
│   │   │   │   ├── 📄 NewsPagination.tsx
│   │   │   │   ├── 📄 NewsShareButton.tsx
│   │   │   │   ├── 📄 NewsSidebar.tsx
│   │   │   │   └── 📄 NewsSidebar_new.tsx
│   │   │   ├── 📁 notifications/
│   │   │   │   └── 📄 RateLimitNotifications.tsx
│   │   │   ├── 📁 payment/
│   │   │   │   ├── 📄 BookingInfoStep.tsx
│   │   │   │   ├── 📄 CompletionStep.tsx
│   │   │   │   ├── 📄 PaymentCheck.tsx
│   │   │   │   ├── 📄 PaymentStep.tsx
│   │   │   │   ├── 📄 PaymentSummary.tsx
│   │   │   │   └── 📄 index.ts
│   │   │   ├── 📁 profile/
│   │   │   │   ├── 📄 BookingManagement.tsx
│   │   │   │   ├── 📄 ChangePassword.tsx
│   │   │   │   ├── 📄 ForgotPassword.tsx
│   │   │   │   ├── 📄 Notifications.tsx
│   │   │   │   ├── 📄 PersonalInfo.tsx
│   │   │   │   ├── 🎨 ProfileLayout.module.css
│   │   │   │   ├── 📄 ProfileLayout.tsx
│   │   │   │   ├── 📄 Settings.tsx
│   │   │   │   ├── 📄 Wishlist.tsx
│   │   │   │   └── 📄 index.ts
│   │   │   ├── 📁 reception/
│   │   │   │   ├── 📄 ReceptionLayout.tsx
│   │   │   │   └── 📄 index.ts
│   │   │   ├── 📁 room/
│   │   │   │   ├── 📄 RoomActionBar.tsx
│   │   │   │   ├── 📄 RoomBookingBar.tsx
│   │   │   │   ├── 📄 RoomCommentSection.tsx
│   │   │   │   ├── 📄 RoomDescription.tsx
│   │   │   │   ├── 📄 RoomFacilities.tsx
│   │   │   │   ├── 📄 RoomGallery.tsx
│   │   │   │   ├── 📄 RoomInfo.tsx
│   │   │   │   ├── 📄 RoomPolicyModal.tsx
│   │   │   │   ├── 📄 RoomRatingStats.tsx
│   │   │   │   └── 📄 RoomRelated.tsx
│   │   │   ├── 📁 room-management/
│   │   │   │   ├── 📄 FilterBar.tsx
│   │   │   │   ├── 📖 README.md
│   │   │   │   ├── 📄 RoomCardGrid.tsx
│   │   │   │   ├── 📄 RoomCardGridDemo.tsx
│   │   │   │   ├── 📄 RoomCheckCardList.tsx
│   │   │   │   ├── 📄 RoomCheckCardListV2.tsx
│   │   │   │   ├── 📄 RoomTimelineView.tsx
│   │   │   │   └── 📄 bug.json
│   │   │   ├── 📁 roomTypes/
│   │   │   │   ├── 📄 RoomAmenities.tsx
│   │   │   │   ├── 📄 RoomAvailabilityFilter.tsx
│   │   │   │   ├── 📄 RoomBookingForm.tsx
│   │   │   │   ├── 📄 RoomImageGallery.tsx
│   │   │   │   ├── 📄 RoomReviews.tsx
│   │   │   │   ├── 📄 RoomServiceOptions.tsx
│   │   │   │   └── 📄 SimilarRooms.tsx
│   │   │   ├── 📁 search/
│   │   │   │   ├── 📄 AnchorNavigation.tsx
│   │   │   │   ├── 📄 BookingFloatButton.tsx
│   │   │   │   ├── 📄 BookingSummary.tsx
│   │   │   │   ├── 📄 ImageGalleryModal.tsx
│   │   │   │   ├── 📄 NotificationSystem.tsx
│   │   │   │   ├── 📄 RoomCard.tsx
│   │   │   │   ├── 📄 RoomDetailModal.tsx
│   │   │   │   ├── 📄 RoomOptionsSection.tsx
│   │   │   │   └── 📄 RoomTypeSection.tsx
│   │   │   ├── 📁 ui/
│   │   │   │   ├── 📄 Ai.promt
│   │   │   │   ├── 📄 Awards.tsx
│   │   │   │   ├── 📄 Breadcrumb.tsx
│   │   │   │   ├── 📄 ButtonSearch.tsx
│   │   │   │   ├── 📄 ChatBot.tsx
│   │   │   │   ├── 📄 ContactFloatButton.tsx
│   │   │   │   ├── 📄 FeatureCard.tsx
│   │   │   │   ├── 📄 HeroBanner.tsx
│   │   │   │   ├── 📄 HotelActivities.tsx
│   │   │   │   ├── 📄 HotelAmenities.tsx
│   │   │   │   ├── 📄 HotelCard.tsx
│   │   │   │   ├── 📄 LanguageSwitcher.tsx
│   │   │   │   ├── 📄 LazyLoad.tsx
│   │   │   │   ├── 📄 RainbowButton.tsx
│   │   │   │   ├── 🎨 RoomCard.css
│   │   │   │   ├── 📄 RoomCardsGrid.tsx
│   │   │   │   ├── 📄 RoomSwiper.tsx
│   │   │   │   ├── 📄 RoomTypeCard.tsx
│   │   │   │   ├── 🎨 RoomTypeShowcase.css
│   │   │   │   ├── 📄 RoomTypeShowcase.tsx
│   │   │   │   ├── 📄 RoomTypeShowcaseNew.tsx
│   │   │   │   ├── 📄 StyledHeroBanner.tsx
│   │   │   │   ├── 📄 StyledTitle.tsx
│   │   │   │   ├── 📄 ThemeToggle.tsx
│   │   │   │   ├── 📄 TravelExperience.tsx
│   │   │   │   ├── 📄 index.ts
│   │   │   │   └── 📄 swiftPanda.tsx
│   │   │   ├── 📄 ContactForm.tsx
│   │   │   ├── 📄 FeatureCard.tsx
│   │   │   ├── 📄 HotelCard.tsx
│   │   │   ├── 📄 Navbar.tsx
│   │   │   ├── 📄 Newsletter.tsx
│   │   │   ├── 📄 PageHeader.tsx
│   │   │   ├── 📄 SearchForm.tsx
│   │   │   ├── 📄 SectionHeader.tsx
│   │   │   ├── 📄 Stats.tsx
│   │   │   ├── 📄 Testimonial.tsx
│   │   │   └── 📄 UserAvatar.tsx
│   │   ├── 📁 config/
│   │   │   ├── 📄 axios.ts
│   │   │   ├── 📄 constants.ts
│   │   │   ├── 📄 env.ts
│   │   │   ├── 📄 index.ts
│   │   │   └── 📄 theme.ts
│   │   ├── 📁 constants/
│   │   │   ├── 📄 Icons.tsx
│   │   │   └── 📄 roomStatus.ts
│   │   ├── 📁 contexts/
│   │   │   ├── 📄 RoomTypesContext.tsx
│   │   │   └── 📄 SearchContext.tsx
│   │   ├── 📁 hooks/
│   │   │   ├── 📄 index.ts
│   │   │   ├── 📄 useApi.ts
│   │   │   ├── 📄 useBookingManager.ts
│   │   │   ├── 📄 useBreakpoint.ts
│   │   │   ├── 📄 useDashboard.ts
│   │   │   ├── 📄 useErrorRedirect.ts
│   │   │   ├── 📄 useFormValidation.ts
│   │   │   ├── 📄 useHotels.ts
│   │   │   ├── 📄 useNews.ts
│   │   │   ├── 📄 useReception.ts
│   │   │   ├── 📄 useReceptionChart.ts
│   │   │   ├── 📄 useRoomAvailability.ts
│   │   │   ├── 📄 useRoomTypes.ts
│   │   │   ├── 📄 useRooms.ts
│   │   │   ├── 📄 useScroll.ts
│   │   │   ├── 📄 useSearch.ts
│   │   │   └── 📄 useThemeMode.ts
│   │   ├── 📁 locales/
│   │   │   ├── 📁 en/
│   │   │   │   └── 📄 translation.json
│   │   │   └── 📁 vi/
│   │   │       └── 📄 translation.json
│   │   ├── 📁 mirage/
│   │   │   ├── 📄 models.ts
│   │   │   ├── 📄 reviews.ts
│   │   │   ├── 📄 roomoption.ts
│   │   │   ├── 📄 server.ts
│   │   │   └── 📄 users.ts
│   │   ├── 📁 pages/
│   │   │   ├── 📁 dashboard/
│   │   │   │   ├── 📄 Bookings.tsx
│   │   │   │   └── 📄 Dashboard.tsx
│   │   │   ├── 📁 reception/
│   │   │   │   ├── 📁 booking-management/
│   │   │   │   │   ├── 📁 components/
│   │   │   │   │   │   ├── 📄 BookedDetailsTab.tsx
│   │   │   │   │   │   ├── 📄 ChangeRoomTab.tsx
│   │   │   │   │   │   ├── 📄 CheckOutTab.tsx
│   │   │   │   │   │   ├── 📄 EarlyCheckOutTab.tsx
│   │   │   │   │   │   ├── 📄 ExtendStayTab.tsx
│   │   │   │   │   │   ├── 📄 InvoiceTab.tsx
│   │   │   │   │   │   ├── 📄 LateCheckOutTab.tsx
│   │   │   │   │   │   ├── 📄 PaymentTab.tsx
│   │   │   │   │   │   ├── 📄 RescheduleTab.tsx
│   │   │   │   │   │   ├── 📄 RoomDetailsTab.tsx
│   │   │   │   │   │   └── 📄 index.ts
│   │   │   │   │   ├── 📄 API.json
│   │   │   │   │   ├── 📄 BookingDetailModal.tsx
│   │   │   │   │   ├── 📄 BookingManagement.tsx
│   │   │   │   │   ├── 📄 CheckinModal.tsx
│   │   │   │   │   ├── 📄 RoomSelectionModal.tsx
│   │   │   │   │   ├── 📄 component.tsx
│   │   │   │   │   ├── 📄 index.ts
│   │   │   │   │   └── 📄 policy.txt
│   │   │   │   ├── 📁 room-management/
│   │   │   │   │   ├── 📄 ConfirmRepresentativePayment.tsx
│   │   │   │   │   ├── 📄 PaymentBookingReception.tsx
│   │   │   │   │   ├── 📄 PaymentSuccess.tsx
│   │   │   │   │   ├── 📄 ReceptionBookRoom.tsx
│   │   │   │   │   ├── 📄 RoomManagementDashboard.tsx
│   │   │   │   │   ├── 📄 promt.txt
│   │   │   │   │   └── 📄 template.tsx
│   │   │   │   ├── 📄 Reception.tsx
│   │   │   │   ├── 📄 ReceptionDashboard.tsx
│   │   │   │   └── 📄 index.ts
│   │   │   ├── 📄 About.tsx
│   │   │   ├── 📄 AdminPayment.tsx
│   │   │   ├── 📄 BookingConfirmation.tsx
│   │   │   ├── 📄 ErrorPage.tsx
│   │   │   ├── 📄 ErrorTestPage.tsx
│   │   │   ├── 📄 Forbidden.tsx
│   │   │   ├── 📄 Home.tsx
│   │   │   ├── 📄 HotelDetailsPage.tsx
│   │   │   ├── 📄 HotelListingPage.tsx
│   │   │   ├── 📄 News.tsx
│   │   │   ├── 📄 NotFound.tsx
│   │   │   ├── 📄 Payment.tsx
│   │   │   ├── 📄 PaymentPage.tsx
│   │   │   ├── 📄 ReviewBooking.tsx
│   │   │   ├── 📄 RoomTypesDemo.tsx
│   │   │   ├── 📄 RoomTypesDetailsPage.1.tsx
│   │   │   ├── 📄 RoomTypesDetailsPage.tsx
│   │   │   ├── 🎨 SearchResults.module.css
│   │   │   ├── 📄 SearchResults.tsx
│   │   │   ├── 📄 ServerError.tsx
│   │   │   ├── 📄 cpay.txt
│   │   │   ├── 📄 searchRessultlayout.txt
│   │   │   └── 📄 tétt.txt
│   │   ├── 📁 providers/
│   │   │   ├── 📄 GoogleAuthProvider.tsx
│   │   │   ├── 📄 QueryProvider.tsx
│   │   │   └── 📄 ThemeProvider.tsx
│   │   ├── 📁 routes/
│   │   │   ├── 📄 PrivateRoute.tsx
│   │   │   ├── 📄 PublicRoute.tsx
│   │   │   └── 📄 index.tsx
│   │   ├── 📁 services/
│   │   │   ├── 📄 apiService.ts
│   │   │   ├── 📄 authService.ts
│   │   │   ├── 📄 bookingAntiSpamService.ts
│   │   │   ├── 📄 bookingService.ts
│   │   │   ├── 📄 chatService.ts
│   │   │   ├── 📄 index.ts
│   │   │   ├── 📄 mockRoomService.ts
│   │   │   ├── 📄 mockSearchService.ts
│   │   │   ├── 📄 newsApi.ts
│   │   │   ├── 📄 newsApi_new.ts
│   │   │   ├── 📄 paymentService.ts
│   │   │   ├── 📄 profileService.ts
│   │   │   ├── 📄 receptionChartApi.ts
│   │   │   ├── 📄 searchService.ts
│   │   │   ├── 📄 userService.ts
│   │   │   └── 📄 wishlistService.ts
│   │   ├── 📁 store/
│   │   │   ├── 📁 slices/
│   │   │   │   ├── 📄 Reception.ts
│   │   │   │   ├── 📄 authSlice.ts
│   │   │   │   ├── 📄 bookingSlice.ts
│   │   │   │   ├── 📄 searchSlice.ts
│   │   │   │   └── 📄 themeSlice.ts
│   │   │   ├── 📄 index.ts
│   │   │   └── 📄 useStore.ts
│   │   ├── 📁 stores/
│   │   │   ├── 📄 roomDetailStore.ts
│   │   │   └── 📄 roomManagementStore.ts
│   │   ├── 📁 styles/
│   │   │   ├── 🎨 global.css
│   │   │   ├── 🎨 performance-optimizations.css
│   │   │   ├── 🎨 theme-transitions.css
│   │   │   ├── 📄 theme-utils.ts
│   │   │   └── 📄 theme.ts
│   │   ├── 📁 types/
│   │   │   ├── 📄 booking.ts
│   │   │   ├── 📄 news.ts
│   │   │   ├── 📄 room.ts
│   │   │   ├── 📄 roomDetail.ts
│   │   │   └── 📄 rooms.ts
│   │   ├── 📁 utils/
│   │   │   ├── 📁 pricing/
│   │   │   │   ├── 📄 cancellationPolicyUtils.ts
│   │   │   │   ├── 📄 deluxeOptions.ts
│   │   │   │   ├── 📄 index.ts
│   │   │   │   ├── 📄 premiumOptions.ts
│   │   │   │   ├── 📄 presidentialOptions.ts
│   │   │   │   ├── 📄 roomPricing.ts
│   │   │   │   ├── 📄 suiteOptions.ts
│   │   │   │   └── 📄 theLevelOptions.ts
│   │   │   ├── 📄 SEO.tsx
│   │   │   ├── 📄 ScrollToTop.tsx
│   │   │   ├── 📄 api.ts
│   │   │   ├── 📄 dynamicPricing.ts
│   │   │   ├── 📄 formatNewsData.ts
│   │   │   ├── 📄 googleOAuthDebug.js
│   │   │   ├── 📄 googleOAuthDebug.ts
│   │   │   ├── 📄 helpers.ts
│   │   │   ├── 📄 hooks.ts
│   │   │   ├── 📄 imageUtils.ts
│   │   │   ├── 📄 index.ts
│   │   │   ├── 📄 performanceOptimization.ts
│   │   │   ├── 📄 qs.d.ts
│   │   │   ├── 📄 roomAllocation.ts
│   │   │   ├── 📄 script.js
│   │   │   ├── 📄 scrollManager.ts
│   │   │   └── 📄 timeHelpers.ts
│   │   ├── 🎨 App.css
│   │   ├── 📄 App.tsx
│   │   ├── 📄 i18n.ts
│   │   ├── 🎨 index.css
│   │   ├── 📄 index.tsx
│   │   ├── 🖼️ logo.svg
│   │   ├── 📄 styles.ts
│   │   ├── 📄 theme.ts
│   │   └── 📄 vite-env.d.ts
│   ├── 🔒 .env 🚫 (auto-hidden)
│   ├── 📄 .eslintrc.cjs
│   ├── 🚫 .gitignore
│   ├── 📄 .gitkeep
│   ├── 📖 README.md
│   ├── 🌐 index.html
│   ├── 📄 package-lock.json
│   ├── 📄 package.json
│   ├── 📄 postcss.config.cjs
│   ├── 📄 tailwind.config.cjs
│   ├── 📄 tsconfig.json
│   ├── 📄 tsconfig.node.json
│   ├── 📄 vite.config.js
│   └── 📄 vitest.config.js
├── 📁 node_modules/ 🚫 (auto-hidden)
├── 📁 storage/
│   └── 📁 logs/
│       └── 📋 laravel.log 🚫 (auto-hidden)
├── 🚫 .gitignore
├── 📓 Frontend_Learning_Roadmap.ipynb
├── 🐚 auto-sync.sh
├── 🗄️ datn_build_basic2 (8).sql
├── 📄 dbdiagram..txt
├── 📄 doc-4.5.txt
├── 📄 doc.txt
├── 📄 gemini
├── 📝 lavishstay.mm.1.md
├── 📝 lavishstay.mm.md
├── 📄 package-lock.json
├── 📄 package.json
├── 🐚 pull-dev.bat
├── 🐚 pull-dev.sh
├── 🐚 push-dev.bat
├── 🐚 push-dev.sh
├── 🐚 start-dev.ps1
├── 🐚 start-dev.sh
├── 🐚 start-fullstack.ps1
├── 🐚 start_backend.bat
├── 🐚 start_frontend.bat
├── 🐚 update-frontend-quyen.ps1
└── 🐚 update-frontend-simple.sh
```

---

_Generated by FileTree Pro Extension_
