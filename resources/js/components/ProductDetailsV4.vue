<template>
    <div class="container-2">
        <div v-show="!isLoading" class="mb-28 md:mb-[195px]">
            <div class="">
                <!-- <div class="flex items-center gap-2 overflow-hidden pt-4">
                    <router-link to="/" class="w-6 h-6">
                        <HomeIcon class="w-5 h-5 text-slate-600" />
                    </router-link>

                    <div class="grow w-full overflow-hidden">
                        <div class="space-x-1 text-slate-600 text-sm font-normal truncate">
                            <router-link to="/">{{ $t("Home") }}</router-link>
                            <span>/</span>
                            <span>{{ product.name }}</span>
                        </div>
                    </div>
                </div> -->

                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-[24px] lg:pb-[32px] pt-6"
                >
                    <div class="w-full px-4 lg:pr-0 2xl:px-0">
                        <div
                            class="bg-slate-50 rounded-tl-xl rounded-tr-xl border-l border-r border-t border-slate-100 overflow-hidden"
                        >
                            <swiper
                                :spaceBetween="10"
                                :thumbs="{ swiper: thumbsSwiper }"
                                :modules="modules"
                                class="product-details-slider"
                            >
                                <swiper-slide
                                    v-for="thumbnail in product.thumbnails"
                                    :key="thumbnail.id"
                                    class="h-full max-h-[450px]"
                                >
                                    <div
                                        v-if="thumbnail.thumbnail"
                                        class="zoom-container h-full"
                                        @mousemove="handleMouseMove"
                                        @mouseleave="resetZoom"
                                        @touchstart="handleMouseMove"
                                        @touchmove="handleMouseMove"
                                        @touchend="resetZoom"
                                    >
                                        <img
                                            :src="thumbnail.thumbnail"
                                            alt="thumbnail"
                                            class="zoom-image h-full w-full object-cover"
                                        />
                                    </div>
                                    <div
                                        v-else
                                        class="h-full w-full bg-slate-200 flex justify-center items-center"
                                    >
                                        <video
                                            v-if="thumbnail.type == 'file'"
                                            controls
                                            class="w-full"
                                        >
                                            <source
                                                :src="thumbnail.url"
                                                type="video/mp4"
                                            />
                                        </video>
                                        <div
                                            v-else
                                            v-html="thumbnail.url"
                                            class="w-full overflow-hidden"
                                            ref="iframeContainer"
                                        ></div>
                                    </div>
                                </swiper-slide>
                            </swiper>
                        </div>

                        <div
                            class="bg-neutral-100 rounded-bl-xl rounded-br-xl py-2 md:py-[16px] overflow-hidden"
                        >
                            <swiper
                                @swiper="setThumbsSwiper"
                                :slidesPerView="7"
                                :freeMode="true"
                                :navigation="false"
                                :watchSlidesProgress="true"
                                :modules="modules"
                                :breakpoints="thumbnailBreakpoints"
                                class="product-details-thumbnail"
                            >
                                <swiper-slide
                                    v-for="thumbnail in product.thumbnails"
                                    :key="thumbnail.id"
                                >
                                    <img
                                        v-if="thumbnail.thumbnail"
                                        :src="thumbnail.thumbnail"
                                        alt=""
                                        class="h-full w-full object-cover"
                                    />

                                    <div
                                        v-else
                                        class="h-full w-full bg-slate-200 flex justify-center items-center"
                                    >
                                        <video
                                            v-if="thumbnail.type == 'file'"
                                            class="h-full w-full"
                                        >
                                            <source
                                                :src="thumbnail.url"
                                                type="video/mp4"
                                            />
                                        </video>
                                        <div
                                            v-else
                                            class="h-full w-full overflow-hidden flex justify-center items-center"
                                        >
                                            <img
                                                :src="'/assets/icons/video-player.svg'"
                                                alt=""
                                                width="70"
                                                height="70"
                                            />
                                        </div>
                                    </div>
                                </swiper-slide>
                            </swiper>
                        </div>
                    </div>

                    <div class="w-full px-4 lg:pl-0 2xl:px-0">
                        <!-- Flash Sale  -->
                        <div
                            v-if="flashSale"
                            class="bg-slate-100 mb-3 sm:mb-6 rounded-lg sm:rounded-[44px] flex items-center justify-start gap-2 sm:gap-5 overflow-hidden flex-col sm:flex-row"
                        >
                            <div
                                class="px-4 sm:px-8 py-2 bg-gradient-to-l from-primary to-primary-800 w-full sm:w-auto"
                            >
                                <div
                                    class="text-white text-sm sm:text-base font-bold leading-normal"
                                >
                                    {{ $t("Flash Sale") }}
                                </div>
                            </div>

                            <div
                                class="h-full flex justify-center items-center flex-wrap pb-2 sm:pb-0"
                            >
                                <div
                                    class="text-center text-primary text-sm font-normal leading-tight pr-2"
                                >
                                    {{ $t("Ending in") }}
                                </div>

                                <!-- Days, Hours, Minutes, Seconds -->
                                <div
                                    class="flex justify-center items-center gap-1 text-white"
                                >
                                    <div
                                        v-if="endDay > 0"
                                        class="p-1 justify-center items-center gap-1 inline-flex"
                                    >
                                        <div
                                            class="text-center text-primary text-base font-semibold font-['Inter'] leading-none"
                                        >
                                            {{ endDay }}
                                        </div>
                                        <div
                                            class="text-center text-[#687387] text-[9.14px] font-normal font-['Inter'] leading-none"
                                        >
                                            {{ $t("Days") }}
                                        </div>
                                    </div>

                                    <span
                                        v-if="endDay > 0"
                                        class="text-black text-base font-bold"
                                        >:</span
                                    >
                                    <div
                                        class="p-1 justify-center items-center gap-1 inline-flex"
                                    >
                                        <div
                                            class="text-center text-primary text-base font-semibold font-['Inter']"
                                        >
                                            {{ endHour }}
                                        </div>
                                        <div
                                            class="text-center text-[#687387] text-[9.14px] font-normal font-['Inter'] leading-none"
                                        >
                                            {{ $t("Hours") }}
                                        </div>
                                    </div>

                                    <span class="text-black text-base font-bold"
                                        >:</span
                                    >
                                    <div
                                        class="p-1 justify-center items-center gap-1 inline-flex"
                                    >
                                        <div
                                            class="text-center text-primary text-base font-semibold font-['Inter']"
                                        >
                                            {{ endMinute }}
                                        </div>
                                        <div
                                            class="text-center text-[#687387] text-[9.14px] font-normal font-['Inter'] leading-none"
                                        >
                                            {{ $t("Minutes") }}
                                        </div>
                                    </div>

                                    <span
                                        v-if="endDay <= 0"
                                        class="text-black text-base font-bold"
                                        >:</span
                                    >
                                    <div
                                        v-if="endDay <= 0"
                                        class="p-1 justify-center items-center gap-1 inline-flex"
                                    >
                                        <div
                                            class="text-center text-primary text-base font-semibold font-['Inter']"
                                        >
                                            {{ endSecond }}
                                        </div>
                                        <div
                                            class="text-center text-[#687387] text-[9.14px] font-normal font-['Inter'] leading-none"
                                        >
                                            {{ $t("Seconds") }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Flash Sale -->

                        <!-- Brand -->
                        <p
                            class="text-primary text-xs font-medium font-['Lato'] leading-tight px-1.5 py-1 bg-primary-50 rounded w-fit mb-4"
                        >
                            {{ product.brand ?? "Unknown Brand" }}
                        </p>

                        <!-- Title -->

                        <div
                            class="self-stretch justify-start text-slate-950 text-3xl font-semibold font-['Lato'] leading-10 normal-case"
                        >
                            {{ product.name }}
                        </div>

                        <!-- Short Description -->
                        <div
                            class="mt-4 text-slate-700 text-base font-normal font-['Lato'] leading-normal"
                        >
                            {{ product.short_description }}
                        </div>

                        <!-- Price part -->
                        <div class="flex items-center gap-2 mt-4 flex-wrap">
                            <!-- discount Price -->
                            <div
                                class="text-primary-500 text-[26px] lg:text-3xl font-bold leading-10"
                            >
                                {{
                                    masterStore.showCurrency(
                                        parseFloat(productPrice).toFixed(2)
                                    )
                                }}
                            </div>

                            <!-- Price -->
                            <div
                                v-if="product.discount_price > 0"
                                class="text-slate-400 text-[20px] md:text-xl font-normal leading-7"
                            >
                                {{
                                    masterStore.showCurrency(
                                        parseFloat(mainPrice).toFixed(2)
                                    )
                                }}
                            </div>

                            <!-- discount -->
                            <div
                                v-if="discountPercentage > 0"
                                class="w-fit h-6 px-[10px] py-[2px] bg-error rounded flex justify-center items-center text-xs font-medium font-['Inter'] leading-tight text-white"
                            >
                                {{ discountPercentage }}% {{ $t("OFF") }}
                            </div>
                        </div>

                        <!-- Rating  review, sold and share -->
                        <div
                            class="my-4 flex flex-wrap justify-start items-center gap-[20px] pb-4 border-b border-primary-100"
                        >
                            <!-- rating -->
                            <div class="flex items-center gap-[2px]">
                                <div class="flex items-center">
                                    <StarIcon
                                        class="w-4 h-4 2xl:block hidden text-amber-500"
                                    />
                                </div>
                                <div
                                    class="text-slate-950 text-sm font-semibold font-['Lato'] leading-snug"
                                >
                                    {{ product.rating }}
                                </div>
                                <!-- Review -->
                                <div
                                    class="text-slate-500 text-sm font-normal font-['Lato'] leading-snug"
                                >
                                    ({{ product.total_reviews }})
                                </div>
                            </div>

                            <div
                                class="w-0 h-4 origin-top-left rotate-0 outline outline-1 outline-offset-[-0.50px] outline-slate-200"
                            ></div>

                            <!-- Sold -->
                            <div
                                class="flex justify-start items-center gap-[2px]"
                            >
                                <span class="star">
                                    <img
                                        src="../../../public/assets/images/box-check.png"
                                        alt="check"
                                    />
                                </span>
                                <p
                                    class="text-zinc-900 text-sm font-semibold font-['Lato'] leading-snug"
                                >
                                    {{ product.total_sold }}
                                    <span
                                        class="text-slate-500 text-sm font-normal font-['Lato'] leading-snug"
                                        >{{ $t("Sold") }}</span
                                    >
                                </p>
                            </div>

                            <div
                                class="ml-auto flex justify-center items-center gap-4"
                            >
                                <!-- Share -->
                                <Menu
                                    as="div"
                                    class="relative inline-block text-left"
                                >
                                    <div>
                                        <MenuButton
                                            class="px-2 py-1 bg-primary-100 rounded-lg inline-flex justify-start items-center gap-2"
                                        >
                                            <!-- <ShareIcon
                                                class="w-[16px] text-slate-600"
                                            /> -->
                                            <RiShareLine
                                                class="w-6 h-6 text-slate-600"
                                            />

                                            <span
                                                class="text-slate-800 text-base font-normal font-['Roboto'] leading-normal"
                                            >
                                                {{ $t("Share") }}
                                            </span>
                                        </MenuButton>
                                    </div>

                                    <transition
                                        enter-active-class="transition ease-out duration-100"
                                        enter-from-class="transform opacity-0 scale-95"
                                        enter-to-class="transform opacity-100 scale-100"
                                        leave-active-class="transition ease-in duration-75"
                                        leave-from-class="transform opacity-100 scale-100"
                                        leave-to-class="transform opacity-0 scale-95"
                                    >
                                        <MenuItems
                                            class="absolute right-0 tr z-10 mt-2 w-56 origin-top rounded-md bg-white ring-1 shadow-lg ring-black/5 focus:outline-hidden"
                                        >
                                            <div
                                                class="py-1 divide-y divide-gray-100"
                                            >
                                                <MenuItem
                                                    v-slot="{ active }"
                                                    v-for="social in shareOptions"
                                                    :key="social.name"
                                                    class="cursor-pointer"
                                                    @click="share(social.name)"
                                                >
                                                    <div
                                                        class="flex items-center gap-2 justify-between px-4 py-2 hover:bg-slate-100 transition-all duration-200"
                                                    >
                                                        <div
                                                            class="flex items-center gap-1.5"
                                                        >
                                                            <div
                                                                class="w-7 h-7 p-1.5 flex justify-center items-center text-white rounded-full"
                                                                :class="`bg-[${social.color}]`"
                                                            >
                                                                <FontAwesomeIcon
                                                                    :icon="
                                                                        social.icon
                                                                    "
                                                                    class="w-full h-full"
                                                                />
                                                            </div>
                                                            <span
                                                                class="capitalize"
                                                                >{{
                                                                    social.name
                                                                }}</span
                                                            >
                                                        </div>
                                                        <div
                                                            class="w-5 h-5 p-1 flex justify-center items-center bg-slate-200 rounded-full rotate-45"
                                                        >
                                                            <FontAwesomeIcon
                                                                :icon="
                                                                    faArrowUp
                                                                "
                                                                class="w-full h-full text-slate-500"
                                                            />
                                                        </div>
                                                    </div>
                                                </MenuItem>
                                            </div>
                                        </MenuItems>
                                    </transition>
                                </Menu>

                                <!-- Heart Icon -->
                                <button
                                    class="bg-white/10 rounded-full w-8 h-8 flex justify-center items-center"
                                    @click="favoriteAddOrRemove"
                                >
                                    <RiHeart3Line
                                        v-if="!product.is_favorite"
                                        class="w-[22px] h-[22px] text-zinc-900"
                                    />
                                    <RiHeart3Fill
                                        v-else
                                        class="w-[22px] h-[22px] text-red-500"
                                    />
                                </button>
                            </div>
                        </div>

                        <!-- Qty -->
                        <div
                            v-if="cartProduct"
                            class="hidden lg:flex items-start lg:items-center flex-col md:flex-row gap-3 mb-6"
                        >
                            <div
                                class="max-w-full md:max-w-[140px] w-full text-zinc-800 text-lg font-normal leading-[26px]"
                            >
                                {{ $t("Quantity") }}:
                            </div>

                            <div
                                class="p-1 rounded-[10px] border border-slate-200 inline-flex gap-2 bg-white"
                            >
                                <button
                                    class="bg-gray-100 p-1 rounded"
                                    @click="decrementQty"
                                >
                                    <MinusIcon class="w-5 h-5 text-black" />
                                </button>

                                <div
                                    class="w-6 flex items-center justify-center text-center text-black text-base font-semibold leading-6"
                                >
                                    {{
                                        cartProduct.quantity
                                            ? cartProduct.quantity
                                            : 1
                                    }}
                                </div>

                                <button
                                    class="bg-gray-100 p-1 rounded"
                                    @click="incrementQty"
                                >
                                    <PlusIcon class="w-5 h-5 text-black" />
                                </button>
                            </div>
                        </div>

                        <div
                            class="hidden lg:flex justify-between items-center flex-col md:flex-row gap-4"
                        >
                            <!-- Quantity Increase Or Decrease -->
                            <!-- <div
                                v-if="cartProduct"
                                class="p-2 rounded-[10px] border border-slate-100 inline-flex gap-4"
                            >
                                <button
                                    class="bg-slate-200 p-2 rounded"
                                    @click="decrementQty"
                                >
                                    <MinusIcon class="w-6 h-6 text-slate-800" />
                                </button>

                                <div
                                    class="w-6 flex items-center justify-center text-center text-slate-950 text-base font-medium leading-normal"
                                >
                                    {{ cartProduct.quantity }}
                                </div>

                                <button
                                    class="bg-slate-100 p-2 rounded"
                                    @click="incrementQty"
                                >
                                    <PlusIcon class="w-6 h-6 text-slate-800" />
                                </button>
                            </div> -->

                            <!-- Add to Cart -->
                            <button
                                class="w-full justify-center items-center text-primary flex gap-[10px] p-3 rounded-[8px] border border-primary"
                                @click="addToCart"
                            >
                                <div class="w-5 h-5">
                                    <CartIcon colorClass="fill-primary" />
                                </div>
                                <div
                                    class="text-base font-medium leading-normal"
                                >
                                    {{ $t("Add to Cart") }}
                                </div>
                            </button>

                            <!-- Buy Now -->
                            <button
                                class="w-full justify-center items-center flex gap-[10px] text-white bg-primary p-3 rounded-[8px] border border-primary"
                                @click="buyNow"
                            >
                                <div class="w-5 h-5">
                                    <BagIcon :colorClass="'text-white'" />
                                </div>
                                <span
                                    class="text-base font-medium leading-normal"
                                >
                                    {{ $t("Buy Now") }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-[18px] px-0 lg:px-4 2xl:px-0">
                    <!-- product details section  -->
                    <div class="col-span-12 lg:col-span-8">
                        <!-- <div
                            class="block xl:hidden w-full pt-6 border-slate-200"
                        >
                            <ProductDetailsRightSide
                                :product="product"
                                :popularProducts="popularProducts"
                            />
                        </div> -->

                        <div
                            class="flex items-center gap-4 flex-wrap bg-neutral-100 py-2 px-4 lg:p-4 rounded-lg mb-4"
                        >
                            <button
                                class="h-10 md:h-12 px-6 py-3.5 transition-all duration-200 text-sm md:text-lg font-normal font-['Lato'] leading-snug md:leading-relaxed border rounded-lg flex justify-center items-center"
                                :class="
                                    aboutProduct
                                        ? 'text-primary border-primary bg-white font-semibold'
                                        : 'text-primary-600 border-transparent'
                                "
                                @click="
                                    aboutProduct = true;
                                    review = false;
                                "
                            >
                                {{ $t("About Product") }}
                            </button>

                            <button
                                class="h-10 md:h-12 px-6 py-3.5 transition-all duration-200 text-sm md:text-lg font-normal font-['Lato'] leading-snug md:leading-relaxed border rounded-lg flex justify-center items-center"
                                :class="
                                    review
                                        ? 'text-primary border-primary bg-white font-semibold'
                                        : 'text-primary-600 border-transparent'
                                "
                                @click="showReview()"
                            >
                                {{ $t("Reviews") }}
                            </button>
                        </div>

                        <!-- About Product -->
                        <div
                            v-if="aboutProduct"
                            class="description px-4 2xl:px-0"
                        >
                            <div
                                class="prose max-w-none w-full mt-[15px] mb-[32px]"
                                v-html="product.description"
                            ></div>
                        </div>

                        <!-- Reviews -->
                        <div v-if="review" class="px-4 lg:px-0">
                            <!-- Reviews -->
                            <div class="mt-[15px] mb-[32px]">
                                <p
                                    v-if="reviews.length == 0"
                                    class="text-primary px-4 2xl:px-0 text-base"
                                >
                                    No reviews to show
                                </p>
                                <div class="space-y-6">
                                    <ReviewV4
                                        v-for="review in reviews"
                                        :key="review.id"
                                        :review="review"
                                    />
                                </div>

                                <!-- pagination's -->
                                <div
                                    v-if="reviews.length > 0"
                                    class="flex justify-center lg:justify-end items-center w-full mt-6 gap-4 flex-wrap"
                                >
                                    <div class="">
                                        <vue-awesome-paginate
                                            :total-items="totalReviews"
                                            :items-per-page="perPage"
                                            type="button"
                                            :max-pages-shown="3"
                                            v-model="currentPage"
                                            :hide-prev-next-when-ends="true"
                                            @click="onClickHandler"
                                        >
                                            <template #prev-button>
                                                <button
                                                    class="w-full h-full border-primary border rounded-[10px] flex justify-center items-center"
                                                >
                                                    <ChevronLeftIcon
                                                        class="w-6 h-6 text-slate-600"
                                                    />
                                                </button>
                                            </template>

                                            <template #next-button>
                                                <button
                                                    class="w-full h-full border-primary border rounded-[10px] flex justify-center items-center"
                                                >
                                                    <ChevronRightIcon
                                                        class="w-6 h-6 text-slate-600"
                                                    />
                                                </button>
                                            </template>
                                        </vue-awesome-paginate>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- shop section  -->
                        <div
                            class="p-4 sticky top-40 rounded-xl grid grid-cols-8 gap-4 bg-neutral-100 "
                        >
                            <div class="col-span-8 md:col-span-5">
                                <ShopCardTop2 :shop="product.shop" />
                            </div>
                            <div
                                class="col-span-8 md:col-span-3 space-y-2 md:space-y-4"
                            >
                                <div
                                    class="p-4 bg-white rounded-xl inline-flex justify-start items-center gap-2 w-full"
                                >
                                    <div
                                        class="flex justify-center items-start gap-4"
                                    >
                                        <img
                                            src="../../../public/assets/icons/headset.svg"
                                            alt=""
                                            class="w-12 h-12 relative overflow-hidden"
                                        />
                                        <div
                                            class="inline-flex flex-col justify-center items-start gap-1.5"
                                        >
                                            <p
                                                class="self-stretch justify-start text-zinc-800 text-2xl font-bold font-['Lato'] leading-tight md:leading-loose"
                                            >
                                                24 / 7
                                            </p>
                                            <p
                                                class="justify-start text-slate-500 text-base font-normal font-['Lato'] leading-normal"
                                            >
                                                {{
                                                    $t("Fast Customer Support")
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="p-4 bg-white rounded-xl inline-flex justify-start items-center gap-2 w-full"
                                >
                                    <div
                                        class="flex justify-center items-start gap-4"
                                    >
                                        <img
                                            src="../../../public/assets/icons/bike.svg"
                                            alt=""
                                            class="w-12 h-12 relative overflow-hidden"
                                        />
                                        <div
                                            class="inline-flex flex-col justify-center items-start gap-1.5"
                                        >
                                            <p
                                                class="self-stretch justify-start text-zinc-800 text-2xl font-bold font-['Lato'] leading-tight md:leading-loose"
                                            >
                                                {{ masterStore.showCurrency(product?.shop?.delivery_charge) }}
                                            </p>
                                            <p
                                                class="justify-start text-slate-500 text-base font-normal font-['Lato'] leading-normal"
                                            >
                                                Delivery charge
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="p-4 bg-white rounded-xl inline-flex justify-start items-center gap-2 w-full"
                                >
                                    <div
                                        class="flex justify-center items-start gap-4"
                                    >
                                        <img
                                            src="../../../public/assets/icons/stopwatch.svg"
                                            alt=""
                                            class="w-12 h-12 relative overflow-hidden"
                                        />
                                        <div
                                            class="inline-flex flex-col justify-center items-start gap-1.5"
                                        >
                                            <p
                                                class="self-stretch justify-start text-zinc-800 text-2xl font-bold font-['Lato'] leading-tight md:leading-loose"
                                            >
                                                {{product?.shop?.estimated_delivery_time}}
                                            </p>
                                            <p
                                                class="justify-start text-slate-500 text-base font-normal font-['Lato'] leading-normal"
                                            >
                                                Estimated Delivery Time
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- similar product section  -->
                    <div class="col-span-12 lg:col-span-4 px-4 lg:px-0">
                        <!-- Similar Products -->
                        <div>
                            <div
                                class="text-black text-xl md:text-3xl font-semibold font-['Lato'] leading-7 md:leading-10 lg:bg-neutral-100 lg:py-5 lg:px-4 rounded-lg"
                            >
                                {{ $t("Related Products") }}
                            </div>

                            <div
                                v-if="relatedProducts.length == 0"
                                class="lg:py-5 lg:px-4 text-primary"
                            >
                                <p>No Product to show</p>
                            </div>

                            <div
                                class="hidden lg:grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-1 xl:grid-cols-2 gap-[18px] items-start my-4 lg:my-6"
                            >
                                <div
                                    v-for="product in relatedProducts"
                                    :key="product.id"
                                >
                                    <ProductCardToggler
                                        :productData="product"
                                    />
                                </div>
                            </div>

                            <div
                                :dir="masterStore.langDirection || 'ltr'"
                                class="block lg:hidden pt-4"
                            >
                                <swiper
                                    :breakpoints="breakpoints"
                                    :loop="true"
                                    ref="swiperRef"
                                    @swiper="onSwiper"
                                    :modules="modules"
                                    :pagination="true"
                                    class="related_product_swiper"
                                    :autoplay="{
                                        delay: 4000,
                                        disableOnInteraction: false,
                                    }"
                                >
                                    <swiper-slide
                                        v-for="product in relatedProducts"
                                        :key="product.id"
                                    >
                                        <div class="mb-10">
                                            <ProductCardToggler
                                                :productData="product"
                                            />
                                        </div>
                                    </swiper-slide>
                                </swiper>
                            </div>

                            <div v-if="masterStore.offerBanners.length == 5">
                                <img
                                    :src="
                                        masterStore.offerBanners[4].offer_banner
                                    "
                                    alt=""
                                    loading="lazy"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side -->
            <!-- <div class="hidden xl:block col-span-1 w-full pt-6 h-full xl:pt-16 border-slate-200 xl:pb-6"
                :class="masterStore.langDirection == 'rtl' ? 'xl:pr-8 xl:border-r' : 'xl:pl-8 xl:border-l'">
                <ProductDetailsRightSide :product="product" :popularProducts="popularProducts" />
            </div> -->
        </div>

        <!-- page loader -->
        <div v-if="isLoading" class="px-4 lg:px-0">
            <div class="lg:pr-6">
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-[24px] lg:pb-[32px] pt-6"
                >
                    <div class="w-full lg:shrink-0">
                        <SkeletonLoader
                            class="w-full h-52 md:h-80 rounded-lg"
                        />

                        <div class="flex flex-grow gap-3 mt-4">
                            <SkeletonLoader
                                v-for="i in 4"
                                class="w-20 h-16 grow"
                            />
                        </div>
                        <div class="flex flex-col gap-3 mt-6">
                            <SkeletonLoader class="w-11/12 h-3 rounded-xl" />
                            <SkeletonLoader class="w-10/12 h-3 rounded-xl" />
                            <SkeletonLoader class="w-11/12 h-3 rounded-xl" />
                            <SkeletonLoader class="w-full h-3 rounded-xl" />
                        </div>
                        <div class="flex flex-col gap-4 mt-4">
                            <SkeletonLoader
                                v-for="i in 3"
                                class="w-full h-20 rounded-lg"
                            />
                        </div>
                    </div>

                    <div class="w-full pb-4">
                        <div class="flex flex-col gap-3">
                            <SkeletonLoader class="w-11/12 h-3 rounded-xl" />
                            <SkeletonLoader class="w-10/12 h-3 rounded-xl" />
                            <SkeletonLoader class="w-11/12 h-3 rounded-xl" />
                            <SkeletonLoader class="w-full h-3 rounded-xl" />
                        </div>

                        <div class="flex flex-fow gap-4 mt-4">
                            <SkeletonLoader
                                v-for="i in 2"
                                class="w-full h-20 rounded-lg"
                            />
                        </div>

                        <div class="flex flex-col gap-3 mt-4">
                            <SkeletonLoader class="w-11/12 h-3 rounded-xl" />
                            <SkeletonLoader class="w-10/12 h-3 rounded-xl" />
                            <SkeletonLoader class="w-11/12 h-3 rounded-xl" />
                            <SkeletonLoader class="w-full h-3 rounded-xl" />
                        </div>
                        <div class="flex flex-col gap-4 mt-4">
                            <SkeletonLoader
                                v-for="i in 2"
                                class="w-full h-20 md:h-36 rounded-lg"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side -->
            <!-- <div
                class="hidden xl:block col-span-1 w-full pt-6 h-full xl:pt-16 xl:pl-8 xl:border-l border-slate-200 xl:pb-6"
            >
                <div class="flex flex-col gap-4 mt-4">
                    <SkeletonLoader
                        v-for="i in 5"
                        class="w-full h-20 md:h-36 rounded-lg"
                    />
                </div>
            </div> -->
        </div>
        <!-- end loader -->
    </div>
    <AddToCartPanel
        :showProductQtyCounter="cartProduct ? true : false"
        :qty="cartProduct ? cartProduct.quantity : 1"
        :discountPercentage="discountPercentage"
        :previousPrice="mainPrice"
        :currentPrice="productPrice"
        :hasDiscountPrice="product.discount_price > 0 ? true : false"
        @addToCart="() => addToCart()"
        @buyNow="() => buyNow()"
        @incrementQty="() => incrementQty()"
        @decrementQty="() => decrementQty()"
    />
</template>

<script setup>
import { nextTick, onMounted, onUnmounted, ref, watch } from "vue";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { useRoute, useRouter } from "vue-router";
import { useMaster } from "../stores/MasterStore";

import {
    HeartIcon,
    HomeIcon,
    MinusIcon,
    PlusIcon,
    ShareIcon,
} from "@heroicons/vue/24/outline";
import { HeartIcon as HeartIconFill, StarIcon } from "@heroicons/vue/24/solid";
import {
    FreeMode,
    Navigation,
    Thumbs,
    Pagination,
    A11y,
    Autoplay,
} from "swiper/modules";
import { Swiper, SwiperSlide } from "swiper/vue";

import { useToast } from "vue-toastification";
import { useAuth } from "../stores/AuthStore";
import { useBasketStore } from "../stores/BasketStore";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
    faFacebookF,
    faLinkedin,
    faTwitter,
    faPinterest,
    faRedditAlien,
    faWhatsapp,
    faTelegram,
} from "@fortawesome/free-brands-svg-icons";
import { faEnvelope, faArrowUp } from "@fortawesome/free-solid-svg-icons";
import { useShareLink } from "vue3-social-sharing";
const { shareLink } = useShareLink();

import ProductDetailsRightSide from "../components/ProductDetailsRightSide.vue";
import ToastSuccessMessage from "../components/ToastSuccessMessage.vue";
import BagIcon from "../icons/Bag.vue";
import CartIcon from "../icons/Cart.vue";
import SkeletonLoader from "../components/SkeletonLoader.vue";
import ReviewRatings from "../components/ReviewRatings.vue";
import ProductCard from "../components/ProductCard.vue";
import Review from "../components/Review.vue";

// Import Swiper styles
import "swiper/css";
import "swiper/css/free-mode";
import "swiper/css/navigation";
import "swiper/css/thumbs";
import "swiper/css/pagination";
import {
    RiHeart3Fill,
    RiHeart3Line,
    RiHomeLine,
    RiMailLine,
    RiMapPinLine,
    RiShareLine,
} from "vue-remix-icons";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/20/solid";
import ShopCardTop from "../components/ShopCardTop.vue";
import AddToCartPanel from "../components/AddToCartPanel.vue";
import ShopCardTop2 from "../components/ShopCardTop2.vue";
import ReviewV4 from "./ReviewV4.vue";
import ProductCardV4 from "./ProductCardV4.vue";
import ProductCardNextMart from "./NextMart/ProductCard.vue";
import ProductCardToggler from "./ProductCardToggler.vue";

const toast = useToast();
const route = useRoute();
const router = useRouter();
const masterStore = useMaster();
const basketStore = useBasketStore();
const authStore = useAuth();

const swiperInstance = ref();
const thumbsSwiper = ref(null);
const modules = [FreeMode, Navigation, Thumbs, A11y, Autoplay, Pagination];
const breakpoints = {
    320: {
        slidesPerView: 1.2,
        spaceBetween: 12,
    },
    550: {
        slidesPerView: 2.2,
        spaceBetween: 12,
    },
    768: {
        slidesPerView: 3,
        spaceBetween: 12,
    },
    1024: {
        slidesPerView: 4,
        spaceBetween: 15,
    },
    1440: {
        slidesPerView: 4,
        spaceBetween: 24,
    },
};

const setThumbsSwiper = (swiper) => {
    thumbsSwiper.value = swiper;
};

function onSwiper(swiper) {
    swiperInstance.value = swiper;
}

const swiperNextSlide = () => {
    swiperInstance.value.slideNext();
};
const swiperPrevSlide = () => {
    swiperInstance.value.slidePrev();
};

const formData = ref({
    product_id: route.params.id,
    unit: null,
});

const product = ref({});
const productPrice = ref(0);
const mainPrice = ref(0);
const discountPercentage = ref(0);

const relatedProducts = ref([]);
const popularProducts = ref([]);

const aboutProduct = ref(true);
const review = ref(false);

const cartProduct = ref(null);
const isLoading = ref(true);

onMounted(() => {
    fetchProductDetails();
    window.scrollTo(0, 0);
});

watch(
    formData,
    () => {
        calculateProductPrice();
    },
    { deep: true }
);

const shareOptions = [
    { name: "facebook", icon: faFacebookF, color: "#0d68f1" },
    { name: "linkedin", icon: faLinkedin, color: "#1275b1" },
    { name: "twitter", icon: faTwitter, color: "#47acdf" },
    { name: "pinterest", icon: faPinterest, color: "#bb0f23" },
    { name: "reddit", icon: faRedditAlien, color: "#fc471e" },
    { name: "whatsapp", icon: faWhatsapp, color: "#25d366" },
    { name: "email", icon: faEnvelope, color: "#bb0f23" },
    { name: "telegram", icon: faTelegram, color: "#47acdf" },
];
// 1275b1
const share = (network) => {
    let description = product.value.short_description.replace(/<[^>]*>/g, "");
    let currentURL = window.location.href;
    let thumbnail = product.value.thumbnails[0];

    shareLink({
        network: network,
        url: currentURL,
        title: product.value.name,
        description: description,
        media: thumbnail ? thumbnail.url : null,
        quote: product.value.name,
        hashtags: product.value.meta_keywords,
        twitterUser: product.value.shop?.name,
    });
};

const thumbnailBreakpoints = {
    1440: { slidesPerView: 9, spaceBetween: 8 },
    1200: { slidesPerView: 7, spaceBetween: 8 },
    1024: { slidesPerView: 5, spaceBetween: 8 },
    992: { slidesPerView: 5, spaceBetween: 8 },
    768: { slidesPerView: 5, spaceBetween: 8 },
    320: { slidesPerView: 5, spaceBetween: 8 },
};

const calculateProductPrice = () => {
    if (product.value.discount_price > 0) {
        productPrice.value = product.value.discount_price;
        mainPrice.value = product.value.price;
    } else {
        productPrice.value = product.value.price;
        mainPrice.value = productPrice.value;
    }

    discountPercentage.value = (
        ((mainPrice.value - productPrice.value) / mainPrice.value) *
        100
    ).toFixed(2);
};

const buyNow = () => {
    if (authStore.token === null) {
        return (authStore.loginModal = true);
    }
    basketStore.addToCart(
        {
            product_id: formData.value.product_id,
            is_buy_now: true,
            quantity: 1,
            unit: null,
        },
        product.value
    );

    basketStore.buyNowShopId = product.value?.shop.id;
    router.push({ name: "buynow" });
};

watch(route, async () => {
    await nextTick();
    window.scrollTo(0, 0);
    fetchProductDetails();
    aboutProduct.value = true;
    review.value = false;
});

watch(
    () => basketStore.products,
    () => {
        findProductInCart(product.value.id);
    },
    { deep: true }
);

const findProductInCart = (productId) => {
    let foundProduct = null;
    basketStore.products.forEach((item) => {
        item.products.find((product) => {
            if (product.id == productId) {
                return (foundProduct = product);
            }
        });
    });
    cartProduct.value = foundProduct;
    if (foundProduct) {
        formData.value.unit = foundProduct.unit;
    }
};

const addToCart = () => {
    console.log(formData.value);
    basketStore.addToCart(formData.value, product.value);
    setTimeout(() => {
        findProductInCart(product.value.id);
    }, 200);
};

const decrementQty = () => {
    basketStore.decrementQuantity(product.value);
    setTimeout(() => {
        findProductInCart(product.value.id);
    }, 200);
};

const incrementQty = () => {
    basketStore.incrementQuantity(product.value);
    setTimeout(() => {
        findProductInCart(product.value.id);
    }, 200);
};

const favoriteAddOrRemove = () => {
    if (authStore.token === null) {
        return (authStore.loginModal = true);
    }
    axios
        .post(
            "/favorite-add-or-remove",
            {
                product_id: product.value.id,
            },
            {
                headers: {
                    Authorization: authStore.token,
                },
            }
        )
        .then(() => {
            product.value.is_favorite = !product.value.is_favorite;
            if (product.value.is_favorite === false) {
                const content = {
                    component: ToastSuccessMessage,
                    props: {
                        title: "Product removed from favorite",
                        message: "Product removed from favorite successfully",
                    },
                };
                toast(content, {
                    type: "default",
                    hideProgressBar: true,
                    icon: false,
                    position: "top-right",
                    toastClassName: "vue-toastification-alert",
                    timeout: 3000,
                });
            } else {
                const content = {
                    component: ToastSuccessMessage,
                    props: {
                        title: "Product added to favorite",
                        message: "Product added to favorite successfully",
                    },
                };
                toast(content, {
                    type: "default",
                    hideProgressBar: true,
                    icon: false,
                    position: "top-right",
                    toastClassName: "vue-toastification-alert",
                    timeout: 3000,
                });
            }
            authStore.fetchFavoriteProducts();
        })
        .catch((error) => {
            console.log(error);
        });
};

const showReview = () => {
    aboutProduct.value = false;
    review.value = true;
    fetchReviews();
};

const flashSale = ref({});
const fetchProductDetails = async () => {
    isLoading.value = true;
    axios
        .get("/product-details", {
            params: { slug: route.params.slug },
            headers: {
                Authorization: authStore.token,
            },
        })
        .then((response) => {
            product.value = response.data.data.product;
            relatedProducts.value = response.data.data.related_products;
            popularProducts.value = response.data.data.popular_products;
            flashSale.value = response.data.data.product.flash_sale;

            if (flashSale.value) {
                startCountdown();
            }

            calculateProductPrice();
            findProductInCart(response.data.data.product.id);
            formData.value.product_id = response.data.data.product.id;
            setTimeout(() => {
                isLoading.value = false;
            }, 100);
        });
};

const averageRatings = ref({});

const totalReviews = ref(0);
const reviews = ref([]);

const currentPage = ref(1);
const perPage = ref(6);

const onClickHandler = (page) => {
    currentPage.value = page;
    fetchReviews();
};

const fetchReviews = async () => {
    axios
        .get("/reviews", {
            params: {
                product_id: route.params.id,
                page: currentPage.value,
                per_page: perPage.value,
            },
        })
        .then((response) => {
            totalReviews.value = response.data.data.total;
            reviews.value = response.data.data.reviews;
            averageRatings.value = response.data.data.average_rating_percentage;
        });
};

const endDay = ref("");
const endHour = ref("");
const endMinute = ref("");
const endSecond = ref("");
let countdownInterval = null;

const startCountdown = () => {
    const endDate = new Date(flashSale.value?.end_date).getTime();

    if (flashSale.value?.end_date) {
        countdownInterval = setInterval(() => {
            const now = new Date().getTime();
            const timeLeft = endDate - now;

            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                endDay.value = "00";
                endHour.value = "00";
                endMinute.value = "00";
                endSecond.value = "00";
            } else {
                endDay.value = String(
                    Math.floor(timeLeft / (1000 * 60 * 60 * 24))
                ).padStart(2, "0");
                endHour.value = String(
                    Math.floor(
                        (timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
                    )
                ).padStart(2, "0");
                endMinute.value = String(
                    Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60))
                ).padStart(2, "0");
                endSecond.value = String(
                    Math.floor((timeLeft % (1000 * 60)) / 1000)
                ).padStart(2, "0");
            }
        }, 1000);
    }
};

onUnmounted(() => {
    clearInterval(countdownInterval);
});

// Position variables to control zoom position
const mouseX = ref(0);
const mouseY = ref(0);

const handleMouseMove = (event) => {
    const container = event.currentTarget;
    const rect = container.getBoundingClientRect();

    let clientX, clientY;
    if (event.type === "touchmove" || event.type === "touchstart") {
        const touch = event.touches[0];
        clientX = touch.clientX;
        clientY = touch.clientY;
    } else {
        clientX = event.clientX;
        clientY = event.clientY;
    }

    // Calculate mouse position as a percentage of the container dimensions
    mouseX.value = ((clientX - rect.left) / rect.width) * 100;
    mouseY.value = ((clientY - rect.top) / rect.height) * 100;
};

const resetZoom = () => {
    mouseX.value = 50;
    mouseY.value = 50;
};

watch([mouseX, mouseY], ([x, y]) => {
    document.documentElement.style.setProperty("--mouse-x", `${x}%`);
    document.documentElement.style.setProperty("--mouse-y", `${y}%`);
});
</script>

<style scoped>
.zoom-container {
    overflow: hidden;
    position: relative;
    cursor: zoom-in;
    width: 100%;
    height: 100%;
}

.zoom-image {
    transition: transform 0.3s ease, transform-origin 0.3s ease;
    transform: scale(1);
    transform-origin: center center;
}

.zoom-container:hover .zoom-image {
    transform: scale(3.5);
    transform-origin: calc(var(--mouse-x, 50%)) calc(var(--mouse-y, 50%));
}
</style>

<style>
.related_product_swiper .swiper-pagination-bullet {
    margin-top: 28px;
    background: white;

    opacity: 1;
    @apply md:hidden border border-gray-200;
}

.related_product_swiper .swiper-pagination-bullet-active {
    @apply bg-primary w-6 h-2 rounded-lg transition-all duration-200 border-primary;
}

.description img {
    max-width: 95% !important;
}

iframe {
    width: 100%;
    height: 300px !important;
}

@media (max-width: 500px) {
    iframe {
        height: 200px !important;
    }
}

@media (max-width: 375px) {
    iframe {
        height: 180px !important;
    }
}

@media (max-width: 320px) {
    iframe {
        height: 160px !important;
    }
}

.product-details-slider .swiper-slide {
    height: auto !important;
}

.product-details-thumbnail .swiper-wrapper {
    @apply items-center;
}

.product-details-thumbnail .swiper-slide {
    @apply w-10 h-12 md:w-16 md:h-16 cursor-pointer transition-all duration-200;
}

.product-details-thumbnail .swiper-button-prev,
.product-details-thumbnail .swiper-button-next {
    @apply bg-white w-6 h-6 rounded-full shadow border border-slate-200 text-slate-600 -translate-y-1/2 mt-0;
}

.product-details-thumbnail .swiper-button-prev::after,
.product-details-thumbnail .swiper-button-next::after {
    @apply text-base;
}

.product-details-thumbnail .swiper-button-next {
    right: 0px;
}

.product-details-thumbnail .swiper-button-prev {
    left: 0px;
}

.product-details-thumbnail .swiper-slide {
    @apply border border-slate-100 rounded-lg transition overflow-hidden;
}

.product-details-thumbnail .swiper-slide-thumb-active {
    @apply border border-primary w-14 h-14 md:w-20 md:h-20;
}
</style>
