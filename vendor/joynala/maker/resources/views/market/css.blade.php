<style>
    .text-black {
        color: var(--Black, #24262D);
    }

    .text-white {
        color: var(--White, #FFF);
    }

    .text-blue {
        color: var(--Info, #067BFF);
    }

    .text-yellow {
        color: var(--Warning, #FFC107);
    }

    .text-red {
        color: var(--Color-Primary, #DD2C5C);
    }

    /* colors end  */

    .flex {
        display: flex;
    }

    .flex-col {
        flex-direction: column;
    }

    .justify-start {
        justify-content: flex-start
    }

    .justify-center {
        justify-content: center
    }

    .justify-between {
        justify-content: space-between
    }

    .items-center {
        align-items: center
    }

    .flex-wrap {
        flex-wrap: wrap;
    }

    .p-4 {
        padding: 16px;
    }

    .pb-4 {
        padding-bottom: 16px
    }

    .mt-4 {
        margin-top: 16px
    }

    .mb-4 {
        margin-bottom: 16px
    }

    .mt-8 {
        margin-top: 32px
    }


    .mb-8 {
        margin-bottom: 32px
    }

    .gap-2 {
        gap: 8px
    }

    .gap-4 {
        gap: 16px
    }

    .gap-6 {
        gap: 24px
    }

    .w-full {
        width: 100%;
    }

    .h-full {
        height: 100%;
    }

    .w-fit {
        width: fit-content;
    }

    .h-fit {
        height: fit-content;
    }

    .border-b {
        border-bottom: 1px solid var(--Gray-100, #EDEEF1);

    }

    /* marketplace section  */
    .marketplace_section_container {
        display: flex;
        flex-direction: column;
        justify-content: start;
        gap: 24px;
        margin-top: 32px;
    }

    .section_container {
        padding: 24px;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 24px;
        border-radius: 16px;
        background: var(--white, #FFF);
    }

    .section_title {
        color: var(--Black, #24262D);
        font-size: 24px;
        font-style: normal;
        font-weight: 500;
        line-height: 32px;
        /* 133.333% */
    }

    .section_link {
        color: var(--Color-Primary, #DD2C5C);

        font-size: 16px;
        font-style: normal;
        font-weight: 500;
        line-height: 24px;
        /* 150% */
    }


    /* card section start */
    .card_info_container {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .card_info_container p {
        overflow: hidden;
        color: var(--Black, #24262D);
        text-overflow: ellipsis;
        font-size: 18px;
        font-style: normal;
        font-weight: 500;
        line-height: 26px;
        /* 144.444% */
    }

    .card_info_container .technologies>span {
        display: inline-flex;
        height: 18px;
        padding: 10px 6px;
        margin: 4px;
        justify-content: start;
        align-items: center;
        gap: 10px;
        border-radius: 2px;
        background: var(--Gray-50, #F6F7F9);
        color: var(--Black, #24262D);

        /* Subtitle Medium/Regular */
        font-family: "Albert Sans";
        font-size: 12px;
        font-style: normal;
        font-weight: 400;
        line-height: 20px;
        /* 166.667% */
    }



    .card_info_container .services>span {
        display: inline-flex;
        height: 24px;
        padding: 8px 4px;
        margin: 2px;
        justify-content: center;
        align-items: center;
        border-radius: 4px;
        background: var(--Primary-50, #EFF5FF);

        color: var(--Black, #24262D);

        /* Subtitle Medium/Regular */
        font-family: "Albert Sans";
        font-size: 12px;
        font-style: normal;
        font-weight: 400;
        line-height: 20px;
        /* 166.667% */
    }


    .card_info_container .price {
        color: var(--Gray-300, #B3BAC6);
        font-family: "Albert Sans";
        font-size: 18px;
        font-style: normal;
        font-weight: 400;
        line-height: 26px;
        /* 144.444% */
        text-decoration-line: line-through;
    }

    .card_info_container .discount_price {
        color: var(--Black, #24262D);

        /* H6/Semi Bold */
        font-family: "Albert Sans";
        font-size: 24px;
        font-style: normal;
        font-weight: 600;
        line-height: 32px;
        /* 133.333% */
        margin-left: 2px;
    }


    .card_info_container .actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px
    }

    .card_info_container .actions>a {
        border: none;
        width: 100%;
    }

    .card_info_container .actions>a:first-child {
        display: flex;
        justify-content: start;
        align-items: center;

        height: 32px;
        padding: 8px 12px;
        gap: 2px;

        border-radius: 4px;
        border: 1px solid transparent;
        background: var(--Gray-50, #F6F7F9);
        transition: all 0.3s ease-in-out;
    }


    .card_info_container .actions>a:first-child:hover {


        border: 1px solid var(--Primary, #3B82F6);
        color: var(--Primary, #3B82F6);
    }


    .card_info_container .actions>a:last-child {
        position: relative;
        display: flex;
        height: 32px;
        padding: 8px 12px;
        justify-content: space-between;
        align-items: center;
        gap: 2px;
        border-radius: 4px;
        border: 1px solid var(--Primary, #3B82F6);
        color: var(--Primary, #3B82F6);
        background: transparent;
        z-index: 0;
        transition: all 0.3s ease-in-out;
    }



    /* .card_info_container .actions > button:last-child > div{
            position: relative;
            z-index: 2;
        } */
    .card_info_container .actions>a:last-child:hover {
        background: var(--Primary, #3B82F6);
        color: var(--White, #FFF);
    }

    /* .card_info_container .actions > button:last-child::after  {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width:100%;
            height: 100%;
            z-index: 1;
            background: var(--Primary, #3B82F6);
        } */


    /* card section end */

    .tag_rating_container {

        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .tag_rating_container>div {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 4px;
    }

    .tag_rating_container>div>img {
        width: 16px;
        height: 16px;
    }

    .tag_rating_container>div>i {
        width: 16px;
        height: 16px;
    }


    .tag_rating_container>div:first-child>p:nth-of-type(1) {
        color: var(--Black, #24262D);

        /* Subtitle Large/Regular */
        font-family: "Albert Sans";
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 22px;
        /* 157.143% */
    }

    .tag_rating_container>div:first-child>p:nth-of-type(2) {
        color: var(--Black, #24262D);

        /* Subtitle Large/Semi Bold */
        font-family: "Albert Sans";
        font-size: 14px;
        font-style: normal;
        font-weight: 600;
        line-height: 22px;
        /* 157.143% */
    }

    .tag_rating_container>p {
        color: #D7DAE0;
    }

    .tag_rating_container>div:last-child>p:nth-of-type(1) {
        color: var(--Black, #24262D);

        /* Subtitle Large/Semi Bold */
        font-family: "Albert Sans";
        font-size: 14px;
        font-style: normal;
        font-weight: 600;
        line-height: 22px;
        /* 157.143% */
    }

    .tag_rating_container>div:last-child>p:nth-of-type(2) {
        color: var(--Black, #24262D);

        /* Subtitle Large/Regular */
        font-family: "Albert Sans";
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 22px;
        /* 157.143% */
    }


    /* update section  */
    .support_container {
        font-family: var(--font-poppins);
    }

    .update_section_container {
        display: flex;
        flex-direction: column;
        justify-content: start;
        gap: 24px;
        margin-top: 32px;
    }

    .notes_container {
        display: flex;
        padding: 24px;
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
        align-self: stretch;
        border-radius: 16px;
        border-top: 1px solid var(--Error, #F04438);
        border-right: 1px solid var(--Error, #F04438);
        border-bottom: 6px solid var(--Error, #F04438);
        border-left: 1px solid var(--Error, #F04438);
        background: var(--White, #FFF);
    }

    .update_info_container {
        display: flex;
        padding: 24px;
        flex-direction: column;
        align-items: flex-start;
        gap: 32px;
        align-self: stretch;
        border-radius: 16px;
        background: var(--White, #FFF);
    }

    /* .notes_container > p {
            color: var(--Black, #24262D);


            font-size: 32px;
            font-style: normal;
            font-weight: 600;
            line-height: 40px;
        }

        */


    .new_update_container {
        border-radius: 12px;
        background: var(--Black, #24262D);
        width: 100%;
        display: flex;
        flex-direction: column;
        padding: 24px;
        gap: 16px
    }

    .new_update_container>.header {
        display: flex;
        justify-content: start;
        gap: 16px;
    }



    .new_update_container>.header>button {
        margin-left: auto;
        display: flex;
        height: 48px;
        padding: 8px 12px;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        border-radius: 4px;
        background: var(--Color-Primary, #DD2C5C);
        border: none;

        color: var(--White, #FFF);
        font-size: 14px;
        font-style: normal;
        font-weight: 600;
        line-height: 22px;
        /* 157.143% */
    }

    .new_update_container>.notes {
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.08);
        padding: 16px;
    }

    .new_update_container>.notes>p {
        color: var(--White, #FFF);

        font-size: 18px;
        font-style: normal;
        font-weight: 400;
        line-height: 26px;
        /* 144.444% */
    }

    .new_update_container>.notes>p>span {
        font-size: 18px;
        font-style: normal;
        font-weight: 600;
        line-height: 26px;
        /* 144.444% */
        margin-right: 8px
    }

    .new_update_container>p {
        color: var(--White, #FFF);
        font-size: 18px;
        font-style: normal;
        font-weight: 400;
        line-height: 26px;
        /* 144.444% */
    }


    .new_update_container>p>span {
        color: #419AFF;
        font-size: 18px;
        font-style: normal;
        font-weight: 600;
        line-height: 26px;
    }


    .update_summary_container {
        display: flex;
        padding: 16px;
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
        align-self: stretch;
        border-radius: 12px;
        background: var(--Slate-50, #F8FAFC);
    }


    .expire_section_container {
        font-family: var(--font-poppins);
        display: flex;
        padding: 24px;
        flex-direction: column;
        align-items: flex-start;
        gap: 32px;
        align-self: stretch;
        border-radius: 16px;
        background: var(--White, #FFF);
    }

    .expire_notice_container {
        border-radius: 4px;
        background: #FFEFEE;
        width: 100%;
        padding: 16px;
        display: flex;
        justify-content: space-between;
    }

    .expire_notice_container p:first-child {
        color: var(--Error, #F04438);
        font-size: 20px;
        font-style: normal;
        font-weight: 600;
        line-height: 28px;
        /* 140% */
    }

    .expire_notice_container p:last-child {
        color: var(--Black, #24262D);
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 24px;
        /* 150% */
    }

    .version_container {
        display: flex;
        padding: 16px;
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
        align-self: stretch;
        border-radius: 8px;
        background: var(--White, #FFF);
    }

    .version_container p {
        color: var(--Color-Primary, #DD2C5C);
        font-size: 20px;
        font-style: normal;
        font-weight: 600;
        line-height: 28px;
        /* 140% */
    }

    .version_container ul {
        list-style-type: disc;
        list-style-position: inside;
        padding: 0px
    }

    .version_container ul li {
        color: var(--Black, #24262D);
        font-size: 18px;
        font-style: normal;
        font-weight: 400;
        line-height: 26px;
    }

    .update_heading {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .update_heading p:first-child {
        color: var(--Black, #24262D);
        font-size: 32px;
        font-style: normal;
        font-weight: 600;
        line-height: 40px;
        /* 125% */
    }

    .update_heading p:last-child {
        color: var(--Black, #24262D);
        font-size: 18px;
        font-style: normal;
        font-weight: 400;
        line-height: 26px;
        /* 144.444% */
    }


    .page_header {
        background-color: white;
        padding: 16px 12px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        border-radius: 8px;
    }

    .page_header div:first-child {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 16px;

    }

    .header_title {
        color: var(--Gray-Black-950, #262626);
        font-size: 32px;
        font-style: normal;
        font-weight: 500;
        line-height: 40px;
        /* 125% */
    }

    .note {
        display: flex;
        padding: 16px;
        justify-content: space-between;
        align-items: center;
        align-self: stretch;
        border-radius: 4px;
        background: #FFEFEE;
        color: var(--Error, #F04438);

        font-size: 20px;
        font-style: normal;
        font-weight: 500;
        line-height: 28px;
        /* 140% */
    }


    /* heading start */

    .heading {

        font-size: 32px;
        font-style: normal;
        font-weight: 600;
        line-height: 40px;
        /* 125% */
    }

    /* heading end  */

    /* buttons start */

    .common_btn {
        display: flex;
        height: 48px;
        padding: 8px 24px;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        border-radius: 4px;
        background: var(--Color-Primary, #DD2C5C);
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    /* .common_btn:hover{
            color:var(--Color-Primary, #DD2C5C);
            background-color: white;
            border:1px solid var(--Color-Primary, #DD2C5C);
        } */

    .refresh_btn {
        display: flex;
        height: 48px;
        padding: 8px 12px;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        border-radius: 4px;
        border: 1px solid var(--Gray-200, #D7DAE0);
        background: var(--Gray-50, #F6F7F9);
    }

    /* buttons end */

    .tab_container {
        padding-top: 16px;
        display: flex;
        justify-content: start;
    }

    .btn_tab {
        display: flex;
        height: 56px;
        padding: 14px 24px;
        justify-content: center;
        align-items: center;
        gap: 8px;
        color: var(--Gray, #687387);
        text-align: center;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 24px;
        /* 150% */
        border: none;
        background: none;
        cursor: pointer;
    }

    .btn_tab_active {
        border-bottom: 2px solid var(--Color-Primary, #DD2C5C);

        color: var(--Color-Primary, #DD2C5C);
        text-align: center;

        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: 24px;
        /* 150% */
    }

    .btn_version {
        display: flex;
        height: 32px;
        padding: 0 12px;
        justify-content: center;
        align-items: center;
        gap: 10px;
        border-radius: 4px;
        background: rgba(255, 241, 243, 0.15);
        border: none;
        width: fit-content;
        cursor: pointer;
        color: var(--White, #FFF);

        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 24px;
        /* 150% */
    }

    /* Responsive styles */

    /* Tablet (768px - 1023px) */
    @media (min-width: 768px) and (max-width: 1023px) {
        .support_container {
            padding: 16px;
        }

        .page_header {
            padding: 12px;
        }

        .header_title {
            font-size: 28px;
            line-height: 36px;
        }

        .heading {
            font-size: 28px;
            line-height: 36px;
        }

        .update_heading p:first-child {
            font-size: 28px;
            line-height: 36px;
        }

        .update_heading p:last-child {
            font-size: 16px;
            line-height: 24px;
        }

        .expire_notice_container {
            flex-direction: row;
            justify-content: space-between;
        }

        .expire_notice_container p:first-child {
            font-size: 18px;
            line-height: 26px;
        }

        .new_update_container {
            padding: 20px;
        }

        .new_update_container>.header {
            flex-direction: row;
            align-items: center;
        }

        .new_update_container>.header>button {
            margin-left: auto;
            height: 44px;
            font-size: 13px;
        }

        .note {
            font-size: 18px;
            line-height: 26px;
        }

        .version_container p {
            font-size: 18px;
            line-height: 26px;
        }

        .version_container ul li {
            font-size: 16px;
            line-height: 24px;
        }

        .btn_tab {
            padding: 12px 20px;
            font-size: 15px;
        }

        .common_btn,
        .refresh_btn {
            height: 44px;
            padding: 8px 20px;
            font-size: 14px;
        }
    }

    /* Mobile (max-width: 767px) */
    @media (max-width: 767px) {
        .support_container {
            padding: 8px;
        }

        .page_header {
            padding: 8px;
            gap: 12px;
        }

        .page_header div:first-child {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            padding-bottom: 12px;
        }

        .header_title {
            font-size: 24px;
            line-height: 32px;
        }

        .heading {
            font-size: 24px;
            line-height: 32px;
        }

        .update_section_container {
            gap: 16px;
            margin-top: 16px;
        }

        .expire_section_container {
            padding: 16px;
            gap: 24px;
        }

        .expire_notice_container {
            flex-direction: column;
            gap: 8px;
            padding: 12px;
        }

        .expire_notice_container p:first-child {
            font-size: 16px;
            line-height: 24px;
        }

        .expire_notice_container p:last-child {
            font-size: 14px;
            line-height: 22px;
        }

        .update_heading {
            gap: 12px;
        }

        .update_heading p:first-child {
            font-size: 24px;
            line-height: 32px;
        }

        .update_heading p:last-child {
            font-size: 16px;
            line-height: 24px;
        }

        .w-full.flex.justify-start.items-center.gap-4 {
            flex-direction: column;
            gap: 12px;
        }

        .notes_container {
            padding: 16px;
            gap: 12px;
        }

        .note {
            flex-direction: column;
            align-items: flex-start;
            padding: 12px;
            font-size: 16px;
            line-height: 24px;
        }

        .update_info_container {
            padding: 16px;
            gap: 24px;
        }

        .new_update_container {
            padding: 16px;
            gap: 12px;
        }

        .new_update_container>.header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .new_update_container>.header>button {
            margin-left: 0;
            width: 100%;
            justify-content: center;
            height: 44px;
            font-size: 13px;
        }

        .new_update_container>.notes {
            padding: 12px;
        }

        .new_update_container>.notes>p {
            font-size: 16px;
            line-height: 24px;
        }

        .new_update_container>p {
            font-size: 16px;
            line-height: 24px;
        }

        .update_summary_container {
            padding: 12px;
            gap: 12px;
        }

        .version_container {
            padding: 12px;
            gap: 12px;
        }

        .version_container p {
            font-size: 16px;
            line-height: 24px;
        }

        .version_container ul li {
            font-size: 14px;
            line-height: 22px;
        }

        .tab_container {
            flex-wrap: wrap;
            gap: 8px;
            padding-top: 12px;
        }

        .btn_tab {
            height: 48px;
            padding: 10px 16px;
            font-size: 14px;
            line-height: 22px;
        }

        .btn_tab_active {
            font-size: 14px;
            line-height: 22px;
        }

        .common_btn,
        .refresh_btn {
            height: 44px;
            padding: 8px 16px;
            font-size: 13px;
            gap: 12px;
        }

        .btn_version {
            height: 28px;
            padding: 0 10px;
            font-size: 14px;
            line-height: 22px;
        }

        .mt-4 {
            margin-top: 12px;
        }

        .mt-8 {
            margin-top: 24px;
        }

        .mb-4 {
            margin-bottom: 12px;
        }

        .mb-8 {
            margin-bottom: 24px;
        }

        .gap-4 {
            gap: 12px;
        }

        .gap-2 {
            gap: 6px;
        }

        .p-4 {
            padding: 12px;
        }

        .pb-4 {
            padding-bottom: 12px;
        }
    }



    /* Mobile (max-width: 767px) */
    @media (max-width: 320px) {
        .section_title {
            color: var(--Black, #24262D);
            font-size: 20px;
            font-style: normal;
            font-weight: 500;
            line-height: 28px;
            /* 133.333% */
        }

        .section_link {
            color: var(--Color-Primary, #DD2C5C);

            font-size: 12px;
            font-style: normal;
            font-weight: 500;
            line-height: 24px;
            /* 150% */
        }


        .support_container {
            padding: 8px;
        }

        .page_header {
            padding: 8px;
            gap: 12px;
        }

        .page_header div:first-child {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            padding-bottom: 12px;
        }

        .header_title {
            font-size: 20px;
            line-height: 28px;
        }

        .heading {
            font-size: 18px;
            line-height: 26px;
        }

        .update_section_container {
            gap: 16px;
            margin-top: 16px;
        }

        .section_container {
            padding: 8px;
            border-radius: 8px;
        }

        .card_info_container {
            padding: 4px;
        }

        /* card section start */

        .card_info_container p {
            overflow: hidden;
            color: var(--Black, #24262D);
            text-overflow: ellipsis;
            font-size: 14px;
            font-style: normal;
            font-weight: 500;
            line-height: 20px;
            /* 144.444% */
        }

        .card_info_container .actions {

            flex-wrap: wrap;
        }

        .tag_rating_container>p {
            display: none;
        }

        /* card section end  */

        .expire_section_container {
            padding: 8px;
            gap: 24px;
        }

        .expire_notice_container {
            flex-direction: column;
            gap: 8px;
            padding: 12px;
        }

        .expire_notice_container p:first-child {
            font-size: 12px;
            line-height: 20px;
        }

        .expire_notice_container p:last-child {
            font-size: 10px;
            line-height: 18px;
        }

        .update_heading {
            gap: 12px;
        }

        .update_heading p:first-child {
            font-size: 18px;
            line-height: 26px;
        }

        .update_heading p:last-child {
            font-size: 12px;
            line-height: 20px;
        }

        .w-full.flex.justify-start.items-center.gap-4 {
            flex-direction: column;
            gap: 12px;
        }

        .notes_container {
            padding: 8px;
            gap: 12px;
            border-radius: 8px;
        }

        .note {
            flex-direction: column;
            align-items: flex-start;
            padding: 8px;
            font-size: 14px;
            line-height: 22px;
        }

        .update_info_container {
            padding: 0px;
            gap: 24px;
        }

        .new_update_container {
            padding: 8px;
            gap: 12px;
        }

        .new_update_container>.header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .new_update_container>.header>button {
            margin-left: 0;
            width: 100%;
            justify-content: center;
            height: 44px;
            font-size: 13px;
        }

        .new_update_container>.notes {
            padding: 8px;
        }

        .new_update_container>.notes>p {
            font-size: 14px;
            line-height: 20px;
        }


        .new_update_container>.notes>p>span {
            font-size: 14px;
            line-height: 20px;
        }

        .new_update_container>p {
            font-size: 14px;
            line-height: 20px;
        }


        .new_update_container>p>span {
            font-size: 14px;
            line-height: 20px;
        }

        .update_summary_container {
            padding: 12px;
            gap: 12px;
        }

        .version_container {
            padding: 12px;
            gap: 8px;
        }

        .version_container p {
            font-size: 14px;
            line-height: 22px;
        }

        .version_container ul li {
            font-size: 12px;
            line-height: 20px;
        }

        .tab_container {
            flex-wrap: wrap;
            gap: 8px;
            padding-top: 12px;
        }

        .btn_tab {
            height: auto;
            padding: 4px;
            font-size: 12px;
            line-height: 16px;
        }

        .btn_tab_active {
            font-size: 12px;
            line-height: 18px;
        }

        .common_btn,
        .refresh_btn {
            height: 44px;
            padding: 8px 16px;
            font-size: 13px;
            gap: 12px;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .btn_version {
            height: 28px;
            padding: 0 10px;
            font-size: 14px;
            line-height: 22px;
        }

        .mt-4 {
            margin-top: 12px;
        }

        .mt-8 {
            margin-top: 24px;
        }

        .mb-4 {
            margin-bottom: 12px;
        }

        .mb-8 {
            margin-bottom: 24px;
        }

        .gap-4 {
            gap: 12px;
        }

        .gap-2 {
            gap: 6px;
        }

        .p-4 {
            padding: 12px;
        }

        .pb-4 {
            padding-bottom: 12px;
        }
    }
</style>
