<template>
    <div>
        <div class="main-container py-4 bg-slate-100 mt-[25px]">
            <!-- Flash Sale Banner -->
            <!-- <div class="p-3 xl:p-6 flex-col sm:flex-row justify-center items-center gap-1 sm:gap-8 flex rounded-xl mb-3"
                style="background: linear-gradient(90deg, #8B5CF6 0%, #C622FF 36.81%, #5C87F6 75.35%, #8B5CF6 100%);">

                <div class="text-center text-white sm:text-3xl font-bold sm:leading-9">
                    Deal of the Day
                </div>

                <div class="flex justify-center items-center gap-2 xl:gap-4">
                    <div class="text-center text-white text-lg font-normal leading-7 tracking-tight">Ending in</div>

                    Time counter
                    <div class="flex justify-center items-center gap-2 text-white">
                        <div class="w-8 h-8 sm:w-11 sm:h-11 bg-white rounded-full justify-center items-center flex">
                            <span class="text-primary text-lg font-medium">06</span>
                        </div>:
                        <div class="w-8 h-8 sm:w-11 sm:h-11 bg-white rounded-full justify-center items-center flex">
                            <span class="text-primary text-lg font-medium">42</span>
                        </div>:
                        <div class="w-8 h-8 sm:w-11 sm:h-11 bg-white rounded-full justify-center items-center flex">
                            <span class="text-primary text-lg font-medium">19</span>
                        </div>
                    </div>
                </div>
            </div> -->

            <div
                v-if="!isLoading"
                class="w-full p-2 sm:p-4 bg-white rounded-lg sm:rounded-xl md:rounded-2xl flex gap-3 md:gap-6 items-center justify-between"
            >
                <!-- Back -->
                <router-link
                    to="/"
                    class="py-2 flex gap-1 sm:gap-2 items-center justify-center"
                >
                    <ArrowLeftIcon
                        class="w-4 h-4 sm:w-6 sm:h-6 text-slate-600"
                    />
                    <div
                        class="text-slate-600 text-sm sm:text-base font-normal leading-normal"
                    >
                        {{ $t("Back") }}
                    </div>
                </router-link>

                <!-- Categories slider -->
                <div class="grow overflow-x-auto">
                    <swiper
                        :slidesPerView="'auto'"
                        :spaceBetween="16"
                        class="categorySwiper"
                    >
                        <swiper-slide>
                            <div
                                class="p-2 sm:px-4 sm:py-3 rounded-md sm:rounded-[10px] border text-base font-normal leading-normal hover:text-primary cursor-pointer transition duration-300"
                                :class="
                                    !route.query.subcategory
                                        ? 'text-primary border-primary'
                                        : 'border-slate-200 text-slate-600'
                                "
                                @click="
                                    $router.push(
                                        `/categories/${route.params.slug}`
                                    )
                                "
                            >
                                {{ $t("All") }}
                            </div>
                        </swiper-slide>

                        <swiper-slide
                            v-for="subcategory in subcategories"
                            :key="subcategory.id"
                        >
                            <div
                                class="p-2 sm:px-4 sm:py-3 rounded-md sm:rounded-[10px] border text-base font-normal leading-normal hover:text-primary cursor-pointer transition duration-300"
                                :class="
                                    subcategory.id == route.query.subcategory
                                        ? 'text-primary border-primary'
                                        : 'border-slate-200 text-slate-600'
                                "
                                @click="
                                    $router.push(
                                        `/categories/${route.params.slug}?subcategory=${subcategory.id}`
                                    )
                                "
                            >
                                {{ subcategory.name }}
                            </div>
                        </swiper-slide>
                    </swiper>
                </div>

                <!-- Filter button -->
                <div>
                    <button
                        class="p-2 sm:px-4 sm:py-3 rounded-md sm:rounded-[10px] justify-center items-center gap-2 inline-flex text-sm sm:text-base font-normal leading-normal border-0 outline-none hover:text-primary transition duration-300"
                        :class="
                            hasFilter
                                ? ' bg-primary-200 text-primary'
                                : 'text-slate-600 bg-slate-200'
                        "
                        @click="showFilterCanvas = true"
                    >
                        <FunnelIcon class="w-4 h-4 sm:w-6 sm:h-6" />
                        <div class="grow shrink basis-0">
                            {{ $t("Filter") }}
                        </div>
                    </button>
                </div>
            </div>

            <!-- loading -->
            <div
                v-else
                class="w-full p-2 md:p-4 bg-white rounded-lg sm:rounded-xl md:rounded-2xl flex gap-3 md:gap-6 items-center justify-between"
            >
                <SkeletonLoader
                    v-for="i in 2"
                    class="w-24 sm:w-32 md:w-72 lg:w-96 h-12 rounded"
                />
            </div>
        </div>

        <div class="main-container py-12">
            <div
                class="grid grid-cols-1 gap-6 items-start"
                :class="
                    master.themeName == 'UltraMart'
                        ? 'xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5'
                        : 'xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6'
                "
            >
                <div
                    v-if="!isLoading"
                    v-for="product in products"
                    :key="product.id"
                    class="w-full"
                >
                    <ProductCardToggler :productData="product" />
                </div>

                <!-- loading -->
                <div v-else v-for="i in 12" :key="i">
                    <SkeletonLoader
                        class="w-full h-[220px] sm:h-[330px] rounded-lg"
                    />
                </div>
            </div>
            <div
                v-if="products.length == 0 && !isLoading"
                class="flex justify-center items-center w-full mt-8"
            >
                <div
                    class="text-slate-800 text-base font-normal leading-normal"
                >
                    {{ $t("No products found") }}
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="products.length > 0 && !isLoading"
                class="flex justify-between items-center w-full mt-8 gap-4 flex-wrap"
            >
                <div
                    class="text-slate-800 text-base font-normal leading-normal"
                >
                    {{ $t("Showing") }} {{ perPage * (currentPage - 1) + 1 }} to
                    {{ perPage * (currentPage - 1) + products.length }}
                    {{ $t("of") }} {{ totalProducts }} {{ $t("results") }}
                </div>
                <div>
                    <vue-awesome-paginate
                        :total-items="totalProducts"
                        :items-per-page="perPage"
                        type="button"
                        :max-pages-shown="5"
                        v-model="currentPage"
                        :hide-prev-next-when-ends="true"
                        @click="onClickHandler"
                    />
                </div>
            </div>
        </div>

        <!-- Filter Canvas Drawer -->
        <TransitionRoot as="template" :show="showFilterCanvas">
            <Dialog
                as="div"
                class="relative z-50"
                @close="showFilterCanvas = false"
            >
                <TransitionChild
                    as="template"
                    enter="ease-in-out duration-500"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="ease-in-out duration-500"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div
                        class="fixed inset-0 bg-gray-500 bg-opacity-30 transition-opacity"
                    />
                </TransitionChild>

                <div class="fixed inset-0 overflow-hidden">
                    <div class="absolute inset-0 overflow-hidden">
                        <div
                            class="pointer-events-none fixed inset-y-0 flex max-w-full"
                            :class="
                                master.langDirection == 'rtl'
                                    ? 'left-0 sm:pr-10'
                                    : 'right-0 sm:pl-10'
                            "
                        >
                            <TransitionChild
                                as="template"
                                enter="transform transition ease-in-out duration-500 sm:duration-700"
                                :enter-from="
                                    master.langDirection == 'rtl'
                                        ? '-translate-x-full'
                                        : 'translate-x-full'
                                "
                                enter-to="translate-x-0"
                                leave="transform transition ease-in-out duration-500 sm:duration-700"
                                leave-from="translate-x-0"
                                :leave-to="
                                    master.langDirection == 'rtl'
                                        ? '-translate-x-full'
                                        : 'translate-x-full'
                                "
                            >
                                <DialogPanel
                                    class="pointer-events-auto relative w-screen max-w-md"
                                >
                                    <TransitionChild
                                        as="template"
                                        enter="ease-in-out duration-500"
                                        enter-from="opacity-0"
                                        enter-to="opacity-100"
                                        leave="ease-in-out duration-500"
                                        leave-from="opacity-100"
                                        leave-to="opacity-0"
                                    >
                                        <div
                                            class="absolute left-0 top-0 -ml-8 flex pr-2 pt-4 sm:-ml-10 sm:pr-4"
                                        ></div>
                                    </TransitionChild>
                                    <div
                                        class="flex h-full flex-col justify-between overflow-hidden rounded-lg rounded-tr-none rounded-br-none bg-white shadow-xl"
                                    >
                                        <div
                                            class="overflow-y-scroll scrollbar-hide"
                                        >
                                            <div
                                                class="flex justify-between items-center p-4 px-6 sticky top-0 right-0 z-10 bg-neutral-100"
                                            >
                                                <div
                                                    class="text-zinc-900 text-2xl font-semibold leading-loose font-['Poppins']"
                                                >
                                                    {{ $t("Filter") }}
                                                </div>
                                                <button
                                                    class="w-8 h-8 flex justify-center items-center bg-slate-100 rounded-full"
                                                    @click="
                                                        showFilterCanvas = false
                                                    "
                                                >
                                                    <XMarkIcon
                                                        class="w-5 h-5 text-slate-700"
                                                    />
                                                </button>
                                            </div>

                                            <div
                                                class="flex flex-col gap-4 p-4 px-6"
                                            >
                                                <!-- price range -->
                                                <div
                                                    class="border-b border-gray-100 pb-4 space-y-4"
                                                >
                                                    <div
                                                        class="flex justify-between items-center gap-4"
                                                    >
                                                        <div
                                                            class="text-slate-900 text-lg font-['Poppins'] leading-relaxed"
                                                        >
                                                            {{
                                                                $t(
                                                                    "Filter by Price"
                                                                )
                                                            }}
                                                        </div>

                                                        <button
                                                            class="w-14 h-8 px-2.5 py-1.5 bg-primary rounded inline-flex justify-center items-center gap-2 text-white text-sm font-medium font-['Poppins'] leading-snug"
                                                        >
                                                            Filter
                                                        </button>
                                                    </div>

                                                    <div class="w-[98%]">
                                                        <vue-slider
                                                            v-model="priceRange"
                                                            :min="
                                                                filter.min_price
                                                            "
                                                            :max="
                                                                filter.max_price
                                                            "
                                                            :rail-style="
                                                                railStyle
                                                            "
                                                            :process-style="
                                                                processStyle
                                                            "
                                                            :dot-style="
                                                                dotStyle
                                                            "
                                                        ></vue-slider>
                                                    </div>

                                                    <div
                                                        class="flex items-center gap-2"
                                                    >
                                                        <p
                                                            class="text-slate-500 text-base font-normal font-['Poppins'] leading-normal flex-shrink-0"
                                                        >
                                                            Price:
                                                        </p>

                                                        <div
                                                            class="flex items-center gap-1 flex-1 min-w-0"
                                                        >
                                                            <input
                                                                v-model="
                                                                    priceRange[0]
                                                                "
                                                                @input="
                                                                    priceRange =
                                                                        [
                                                                            $event
                                                                                .target
                                                                                .valueAsNumber,
                                                                            priceRange[1],
                                                                        ]
                                                                "
                                                                type="number"
                                                                name="min-price"
                                                                class="h-8 flex-1 min-w-0 px-4 bg-neutral-100 rounded text-zinc-900 text-sm font-normal font-['Poppins'] leading-snug outline outline-1 outline-offset-[-1px] outline-gray-300"
                                                            />

                                                            <p
                                                                class="text-gray-700 text-base font-normal font-['Poppins'] leading-normal px-1"
                                                            >
                                                                -
                                                            </p>

                                                            <input
                                                                v-model="
                                                                    priceRange[1]
                                                                "
                                                                @input="
                                                                    priceRange =
                                                                        [
                                                                            priceRange[0],
                                                                            $event
                                                                                .target
                                                                                .valueAsNumber,
                                                                        ]
                                                                "
                                                                type="number"
                                                                name="max-price"
                                                                class="h-8 flex-1 min-w-0 px-4 bg-neutral-100 rounded text-zinc-900 text-sm font-normal font-['Poppins'] leading-snug outline outline-1 outline-offset-[-1px] outline-gray-300"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Customer Review -->
                                                <div
                                                    class="border-b border-gray-100 pb-4"
                                                >
                                                    <div
                                                        @click="
                                                            toggleFilterExpansionSection(
                                                                'rating'
                                                            )
                                                        "
                                                        class="flex justify-between items-center cursor-pointer"
                                                    >
                                                        <p
                                                            class="justify-center text-zinc-900 text-lg font-medium font-['Poppins'] leading-relaxed"
                                                        >
                                                            {{
                                                                $t(
                                                                    "Filter by Rating"
                                                                )
                                                            }}
                                                        </p>

                                                        <ChevronDownIcon
                                                            class="h-5 w-5 text-gray-800"
                                                        />
                                                    </div>

                                                    <!-- Rating -->
                                                    <div
                                                        class="flex flex-col gap-3"
                                                        :class="
                                                            currentExpandedFilter ===
                                                            'rating'
                                                                ? 'expanded_section_active pt-4 scrollbar-hide'
                                                                : 'expanded_section'
                                                        "
                                                    >
                                                        <div
                                                            v-for="rating in ratings"
                                                            :key="rating"
                                                            class="grow"
                                                        >
                                                            <label
                                                                :for="`rating${rating}`"
                                                                class="cursor-pointer has-[:checked]:border-primary text-slate-800 flex items-center justify-between bg-white rounded-lg gap-1.5"
                                                            >
                                                                <div
                                                                    class="flex items-center gap-[11px]"
                                                                >
                                                                    <input
                                                                        type="radio"
                                                                        v-model="
                                                                            filterFormData.rating
                                                                        "
                                                                        :id="`rating${rating}`"
                                                                        name="review"
                                                                        :value="
                                                                            rating
                                                                        "
                                                                        class="w-4 h-4 appearance-none checked:bg-primary rounded border-2 border-gray-400 shrink-0 transition duration-300"
                                                                    />

                                                                    <div
                                                                        class="flex justify-start items-center gap-1"
                                                                    >
                                                                        <p
                                                                            class="text-slate-800 text-base font-normal font-['Roboto'] leading-normal"
                                                                        >
                                                                            {{
                                                                                rating
                                                                            }}.0
                                                                        </p>

                                                                        <img
                                                                            v-if="
                                                                                rating ==
                                                                                5
                                                                            "
                                                                            class="w-5 h-5"
                                                                            src="../../../public/assets/icons/rating/5-star.svg"
                                                                            alt=""
                                                                        />
                                                                        <img
                                                                            v-if="
                                                                                rating ==
                                                                                4
                                                                            "
                                                                            class="w-5 h-5"
                                                                            src="../../../public/assets/icons/rating/4-star.svg"
                                                                            alt=""
                                                                        />
                                                                        <img
                                                                            v-if="
                                                                                rating ==
                                                                                3
                                                                            "
                                                                            class="w-5 h-5"
                                                                            src="../../../public/assets/icons/rating/3-star.svg"
                                                                            alt=""
                                                                        />
                                                                        <img
                                                                            v-if="
                                                                                rating ==
                                                                                2
                                                                            "
                                                                            class="w-5 h-5"
                                                                            src="../../../public/assets/icons/rating/2-star.svg"
                                                                            alt=""
                                                                        />
                                                                        <img
                                                                            v-if="
                                                                                rating ==
                                                                                1
                                                                            "
                                                                            class="w-5 h-5"
                                                                            src="../../../public/assets/icons/rating/1-star.svg"
                                                                            alt=""
                                                                        />
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Sort by -->
                                                <div
                                                    class="border-b border-gray-100 pb-4 space-y-4"
                                                >
                                                    <p
                                                        class="justify-center text-zinc-900 text-lg font-medium font-['Poppins'] leading-relaxed"
                                                    >
                                                        {{ $t("Sort by") }}
                                                    </p>

                                                    <select
                                                        v-model="
                                                            filterFormData.sort_type
                                                        "
                                                        class="w-full mt-1 p-3 rounded bg-transparent border border-gray-100 outline-none"
                                                    >
                                                        <option
                                                            v-for="shortBy in filterSortBy"
                                                            :key="shortBy"
                                                            :value="
                                                                shortBy.value
                                                            "
                                                        >
                                                            {{ shortBy.name }}
                                                        </option>
                                                    </select>
                                                </div>

                                                <!-- Brand -->
                                                <div
                                                    class="border-b border-gray-100 pb-4"
                                                >
                                                    <div
                                                        class="flex justify-between items-center cursor-pointer"
                                                        @click="
                                                            toggleFilterExpansionSection(
                                                                'brand'
                                                            )
                                                        "
                                                    >
                                                        <p
                                                            class="justify-center text-zinc-900 text-lg font-medium font-['Poppins'] leading-relaxed"
                                                        >
                                                            {{ $t("Brand") }}
                                                        </p>

                                                        <ChevronDownIcon
                                                            class="h-5 w-5 text-gray-800"
                                                        />
                                                    </div>

                                                    <div
                                                        class="flex flex-col gap-3"
                                                        :class="
                                                            currentExpandedFilter ===
                                                            'brand'
                                                                ? 'expanded_section_active pt-4 scrollbar-hide'
                                                                : 'expanded_section'
                                                        "
                                                    >
                                                        <p
                                                            v-if="
                                                                filter.brands
                                                                    .length == 0
                                                            "
                                                            class="text-error text-base font-medium leading-normal capitalize"
                                                        >
                                                            no brands available
                                                        </p>
                                                        <div
                                                            v-for="brand in filter.brands"
                                                            :key="brand.id"
                                                            class="grow"
                                                        >
                                                            <label
                                                                :for="`rating${brand.id}`"
                                                                class="cursor-pointer has-[:checked]:border-primary text-slate-800 flex items-center justify-between bg-white rounded-lg gap-1.5"
                                                            >
                                                                <div
                                                                    class="flex items-center gap-[11px]"
                                                                >
                                                                    <input
                                                                        type="radio"
                                                                        v-model="
                                                                            filterFormData.brand_id
                                                                        "
                                                                        :id="`rating${brand.id}`"
                                                                        name="review"
                                                                        :value="
                                                                            brand.id
                                                                        "
                                                                        class="w-4 h-4 appearance-none checked:bg-primary rounded border-2 border-gray-400 shrink-0 transition duration-300"
                                                                    />

                                                                    <div
                                                                        class="flex justify-start items-center gap-1"
                                                                    >
                                                                        <p
                                                                            class="text-slate-800 text-base font-normal font-['Roboto'] leading-normal"
                                                                        >
                                                                            {{
                                                                                $t(
                                                                                    brand.name
                                                                                )
                                                                            }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- categories -->
                                                <div
                                                    class="border-b border-gray-100 pb-4"
                                                >
                                                    <div
                                                        class="flex justify-between items-center cursor-pointer"
                                                        @click="
                                                            toggleFilterExpansionSection(
                                                                'categories'
                                                            )
                                                        "
                                                    >
                                                        <p
                                                            class="justify-center text-zinc-900 text-lg font-medium font-['Poppins'] leading-relaxed"
                                                        >
                                                            {{ $t("Category") }}
                                                        </p>

                                                        <ChevronDownIcon
                                                            class="h-5 w-5 text-gray-800"
                                                        />
                                                    </div>

                                                    <div
                                                        class="flex flex-col gap-3"
                                                        :class="
                                                            currentExpandedFilter ===
                                                            'categories'
                                                                ? 'expanded_section_active pt-4 scrollbar-hide'
                                                                : 'expanded_section'
                                                        "
                                                    >
                                                        <p
                                                            v-if="
                                                                master
                                                                    .categories
                                                                    .length == 0
                                                            "
                                                            class="text-error text-base font-medium leading-normal capitalize"
                                                        >
                                                            no categories
                                                            available
                                                        </p>
                                                        <div
                                                            v-for="category in master.categories"
                                                            :key="category.id"
                                                            class="grow"
                                                        >
                                                            <label
                                                                :for="`category${category.id}`"
                                                                class="cursor-pointer has-[:checked]:border-primary text-slate-800 flex items-center justify-between bg-white rounded-lg gap-1.5"
                                                            >
                                                                <div
                                                                    class="flex items-center gap-[11px]"
                                                                >
                                                                    <input
                                                                        type="radio"
                                                                        v-model="
                                                                            filterFormData.category_id
                                                                        "
                                                                        :id="`category${category.id}`"
                                                                        name="review"
                                                                        :value="
                                                                            category.id
                                                                        "
                                                                        class="w-4 h-4 appearance-none checked:bg-primary rounded border-2 border-gray-400 shrink-0 transition duration-300"
                                                                    />

                                                                    <div
                                                                        class="flex justify-start items-center gap-1"
                                                                    >
                                                                        <p
                                                                            class="text-slate-800 text-base font-normal font-['Roboto'] leading-normal"
                                                                        >
                                                                            {{
                                                                                $t(
                                                                                    category.name
                                                                                )
                                                                            }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- attributes -->
                                                <div
                                                    v-for="attribute in attributes"
                                                    class="border-b border-gray-100 pb-4"
                                                >
                                                    <div
                                                        class="flex justify-between items-center cursor-pointer"
                                                        @click="
                                                            toggleFilterExpansionSection(
                                                                attribute.name
                                                            )
                                                        "
                                                    >
                                                        <p
                                                            class="justify-center text-zinc-900 text-lg font-medium font-['Poppins'] leading-relaxed"
                                                        >
                                                            {{ attribute.name }}
                                                        </p>

                                                        <ChevronDownIcon
                                                            class="h-5 w-5 text-gray-800"
                                                        />
                                                    </div>

                                                    <div
                                                        class="flex flex-col gap-3"
                                                        :class="
                                                            currentExpandedFilter ===
                                                            attribute.name
                                                                ? 'expanded_section_active pt-4 scrollbar-hide'
                                                                : 'expanded_section'
                                                        "
                                                    >
                                                        <p
                                                            v-if="
                                                                attribute
                                                                    .sub_attributes
                                                                    .length == 0
                                                            "
                                                            class="text-error text-base font-medium leading-normal capitalize"
                                                        >
                                                            no
                                                            {{ attribute.name }}
                                                            available
                                                        </p>
                                                        <div
                                                            v-for="sub_attribute in attribute.sub_attributes"
                                                            :key="
                                                                sub_attribute.id
                                                            "
                                                            class="grow"
                                                        >
                                                            <label
                                                                :for="`sub_attribute${sub_attribute.id}`"
                                                                class="cursor-pointer has-[:checked]:border-primary text-slate-800 flex items-center justify-between bg-white rounded-lg gap-1.5"
                                                            >
                                                                <div
                                                                    class="flex items-center gap-[11px]"
                                                                >
                                                                    <input
                                                                        type="checkbox"
                                                                        v-model="
                                                                            filterFormData.attribute_id
                                                                        "
                                                                        :id="`sub_attribute${sub_attribute.id}`"
                                                                        name="review"
                                                                        :value="
                                                                            sub_attribute.id
                                                                        "
                                                                        class="w-4 h-4 appearance-none checked:bg-primary rounded border-2 border-gray-400 shrink-0 transition duration-300"
                                                                    />

                                                                    <div
                                                                        class="flex justify-start items-center gap-1"
                                                                    >
                                                                        <p
                                                                            class="text-slate-800 text-base font-normal font-['Roboto'] leading-normal"
                                                                        >
                                                                            {{
                                                                                $t(
                                                                                    sub_attribute.name
                                                                                )
                                                                            }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Category -->
                                                <!-- <div>
                                                    <div
                                                        class="text-slate-950 text-base font-medium leading-normal"
                                                    >
                                                        {{ $t("Category") }}
                                                    </div>

                                                    <select
                                                        v-model="
                                                            filterFormData.category_id
                                                        "
                                                        class="w-full mt-1 p-3 rounded bg-transparent border border-gray-100 outline-none"
                                                    >
                                                        <option
                                                            value=""
                                                            selected
                                                        >
                                                            {{
                                                                $t(
                                                                    "Select Category"
                                                                )
                                                            }}
                                                        </option>
                                                        <option
                                                            v-for="category in master.categories"
                                                            :key="category.id"
                                                            :value="category.id"
                                                        >
                                                            {{
                                                                $t(
                                                                    category.name
                                                                )
                                                            }}
                                                        </option>
                                                    </select>
                                                </div> -->

                                                <!-- Brand -->
                                                <!-- <div>
                                                    <div
                                                        class="text-slate-950 text-base font-medium leading-normal"
                                                    >
                                                        {{ $t("Brand") }}
                                                    </div>

                                                    <select
                                                        v-model="
                                                            filterFormData.brand_id
                                                        "
                                                        class="w-full mt-1 p-3 rounded bg-transparent border border-gray-100 outline-none"
                                                    >
                                                        <option
                                                            value=""
                                                            selected
                                                        >
                                                            {{
                                                                $t(
                                                                    "Select Brand"
                                                                )
                                                            }}
                                                        </option>
                                                        <option
                                                            v-for="brand in filter.brands"
                                                            :key="brand.id"
                                                            :value="brand.id"
                                                        >
                                                            {{ $t(brand.name) }}
                                                        </option>
                                                    </select>
                                                </div> -->
                                            </div>
                                        </div>

                                        <!-- button Clear and Apply -->
                                        <div
                                            class="flex gap-6 p-6 border-t border-slate-200"
                                        >
                                            <button
                                                class="grow px-4 py-3 rounded-[10px] border border-primary text-primary text-base font-medium leading-normal"
                                                @click="clearFilter"
                                            >
                                                {{ $t("Clear") }}
                                            </button>
                                            <button
                                                class="grow px-4 py-3 bg-primary rounded-[10px] border border-primary text-white text-base font-medium leading-normal"
                                                @click="applyFilter"
                                            >
                                                {{ $t("Apply") }}
                                            </button>
                                        </div>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </div>
</template>

<script setup>
import {
    Dialog,
    DialogPanel,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import {
    ArrowLeftIcon,
    FunnelIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { StarIcon } from "@heroicons/vue/24/solid";
import { Swiper, SwiperSlide } from "swiper/vue";
import { onMounted, ref, watch } from "vue";
import { useRoute } from "vue-router";
import ProductCardToggler from "../components/ProductCardToggler.vue";
// Import Swiper styles
import "swiper/css";
import SkeletonLoader from "../components/SkeletonLoader.vue";

import { useMaster } from "../stores/MasterStore";

import VueSlider from "vue-slider-component";
import "vue-slider-component/theme/default.css";
import { useAuth } from "../stores/AuthStore";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";

const authStore = useAuth();

const isLoading = ref(true);
const master = useMaster();
const route = useRoute();

const priceRange = ref([0, 1000]);

// range slider styles
const railStyle = {
    height: "4px",
};

const processStyle = {
    backgroundColor: "var(--primary)",
};

const dotStyle = {
    borderColor: "var(--primary)",
    backgroundColor: "var(--primary)",
    borderRadius: "0px",
    width: "8px",
};

onMounted(() => {
    fetchProducts();
    fetchSubCategories();
    fetchAttributes();
    window.scrollTo(0, 0);
});

watch(
    () => route.params.slug,
    () => {
        clearFilter();
        currentPage.value = 1;
        filterFormData.value.category_id = route.params.slug;
        fetchProducts();
        fetchSubCategories();
        fetchAttributes();
    }
);

watch(
    () => route.query.subcategory,
    () => {
        currentPage.value = 1;
        fetchProducts();
    }
);

const currentPage = ref(1);
const perPage = 12;

const onClickHandler = (page) => {
    currentPage.value = page;
    fetchProducts();
};

const showFilterCanvas = ref(false);
const currentExpandedFilter = ref("");

const filterFormData = ref({
    rating: null,
    sort_type: "default",
    brand_id: "",
    color_id: null,
    size_id: null,
    min_price: null,
    max_price: null,
    category_id: route.params.slug,
    attribute_id: [],
});

const filter = ref({
    sizes: [],
    brands: [],
    colors: [],
    min_price: 0,
    max_price: 1000,
});

const ratings = [5, 4, 3, 2, 1];

const categories = master.categories;

const products = ref([]);
const attributes = ref([]);
const totalProducts = ref(0);
const setRangeValue = ref(true);

const hasFilter = ref(false);

const toggleFilterExpansionSection = (section) => {
    if (currentExpandedFilter.value === section) {
        currentExpandedFilter.value = "";
    } else {
        currentExpandedFilter.value = section;
    }
};

watch(
    filterFormData.value,
    () => {
        if (
            filterFormData.value.rating ||
            filterFormData.value.shortBy ||
            filterFormData.value.brand ||
            filterFormData.value.color ||
            filterFormData.value.size
        ) {
            hasFilter.value = true;
        } else {
            hasFilter.value = false;
        }
    },
    { deep: true }
);

const clearFilter = () => {
    filterFormData.value = {
        rating: null,
        short_by: "default",
        brand_id: "",
        color_id: null,
        size_id: null,
        min_price: filter.value.min_price,
        max_price: filter.value.max_price,
        category_id: route.params.slug,
        attribute_id: [],
    };
    priceRange.value = [
        Math.floor(filter.value.min_price),
        Math.floor(filter.value.max_price),
    ];
    setRangeValue.value = true;
};

const subcategories = ref([]);

const fetchProducts = async () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
    isLoading.value = true;
    axios
        .get("/products", {
            params: {
                page: currentPage.value,
                per_page: perPage,
                sub_category_id: route.query.subcategory,
                ...filterFormData.value,
            },
            headers: {
                "Accept-Language": master.locale || "en",
                Authorization: authStore.token,
                currency_id: master.selectedCurrency.id,
            },
        })
        .then((response) => {
            totalProducts.value = response.data.data.total;
            products.value = response.data.data.products;

            filter.value = response.data.data.filters;

            if (setRangeValue.value) {
                priceRange.value = [
                    Math.floor(filter.value.min_price),
                    Math.floor(filter.value.max_price),
                ];
            }

            setTimeout(() => {
                isLoading.value = false;
            }, 200);
        });
};

const applyFilter = () => {
    filterFormData.value.min_price = priceRange.value[0];
    filterFormData.value.max_price = priceRange.value[1];

    setRangeValue.value = false;
    master.search = null;
    currentPage.value = 1;
    showFilterCanvas.value = false;
    fetchProducts();
};

const fetchSubCategories = async () => {
    axios
        .get("/sub-categories?category_id=" + route.params.slug, {
            headers: {
                "Accept-Language": master.locale || "en",
            },
        })
        .then((response) => {
            subcategories.value = response.data.data.sub_categories;
        });
};

const fetchAttributes = async () => {
    axios
        .get("/category/" + route.params.slug + "/attributes", {
            headers: {
                "Accept-Language": master.locale || "en",
            },
        })
        .then((response) => {
            attributes.value = response.data.data.attributes;
        });
};

const filterSortBy = [
    {
        name: "Default Sorting",
        value: "default",
    },
    {
        name: "High to Low",
        value: "high_to_low",
    },
    {
        name: "Low to High",
        value: "low_to_high",
    },
    {
        name: "Most Selling",
        value: "top_selling",
    },
    {
        name: "New Product",
        value: "newest",
    },
];
</script>

<style>
.categorySwiper .swiper-slide {
    width: auto !important;
}

input[type="range"]::-webkit-slider-runnable-track,
input[type="range"]::-ms-track,
input[type="range"]::-moz-range-track {
    background: #000;
}

input[type="range"]::-moz-range-thumb,
input[type="range"]::-ms-thumb,
input[type="range"]::-webkit-slider-thumb {
    background: #000;
}

.expanded_section {
    @apply max-h-0 overflow-hidden transition-all duration-700 ease-in-out;
}

.expanded_section_active {
    @apply max-h-96 overflow-y-scroll overflow-x-hidden  transition-all duration-700 ease-in-out;
}
</style>
