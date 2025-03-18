<script setup>
import Container from '../Components/Container.vue';
import { route } from 'ziggy-js';
</script>
<template>

    <Container class="p-0 relative">
        <div class="w-[400px] top-20 left-20 absolute rounded-md p-6 shadow-md bg-white">
            <h1 class="text-3xl font-medium leading-8 text-gray-800">Mới ghé thăm Udemy? Bạn thật may mắn!</h1>
            <span>Các khóa học có giá từ 299.000 ₫. Nhận ưu đãi học viên mới trước khi ưu đãi hết hạn.</span>
        </div>
        <div>
            <img src="../../../public/storage/banner_home.jpg" alt="">
        </div>
        <!-- hình ảnh logo tài trợ -->
        <div class="mx-auto px-4 py-6 mt-4 mb-4">
            <h2 class="text-md font-medium mb-4 text-center text-gray-700 leading-normal">Được hơn 16.000 công ty và
                hàng triệu học viên trên khắp thế giới tin dùng</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                <div class="flex justify-center items-center">
                    <img src="../../../public/storage/homes/volkswagen_logo.svg" alt="Logo 1"
                        class="h-10 object-contain">
                </div>
                <div class="flex justify-center items-center">
                    <img src="../../../public/storage/homes/samsung_logo.svg" alt="Logo 2" class="h-10 object-contain">
                </div>
                <div class="flex justify-center items-center">
                    <img src="../../../public/storage/homes/cisco_logo.svg" alt="Logo 3" class="h-10 object-contain">
                </div>
                <div class="flex justify-center items-center">
                    <img src="../../../public/storage/homes/vimeo_logo_resized-2.svg" alt="Logo 4"
                        class="h-10 object-contain">
                </div>
                <div class="flex justify-center items-center">
                    <img src="../../../public/storage/homes/procter_gamble_logo.svg" alt="Logo 5"
                        class="h-10 object-contain">
                </div>
                <div class="flex justify-center items-center">
                    <img src="../../../public/storage/homes/hewlett_packard_enterprise_logo.svg" alt="Logo 6"
                        class="h-10 object-contain">
                </div>
                <div class="flex justify-center items-center">
                    <img src="../../../public/storage/homes/citi_logo.svg" alt="Logo 7" class="h-10 object-contain">
                </div>
                <div class="flex justify-center items-center">
                    <img src="../../../public/storage/homes/ericsson_logo.svg" alt="Logo 8" class="h-10 object-contain">
                </div>
            </div>
        </div>

        <!-- Danh sách khóa học nổi bật -->
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-2xl font-semibold mb-4 text-gray-800">Học viên đang xem</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Khóa học 1 -->
                <div class="bg-white rounded shadow overflow-hidden hover:shadow-lg flex flex-col justify-between">
                    <img src="../../../public/storage/courses/Jva.png" alt="Khóa học" class="w-full h-48 object-cover">
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-semibold">Lập trình JavaScript từ A-Z</h3>
                        <p class="text-gray-600 text-sm mt-2 flex-grow">Hướng dẫn chi tiết cho người mới bắt đầu.</p>
                        <div class="mt-2 flex justify-between">
                            <span class="text-yellow-500 font-semibold">4.8 ⭐</span>
                            <span class="text-purple-600 font-semibold">299.000đ</span>
                        </div>
                    </div>
                </div>
                <!-- Khóa học 2 -->
                <div class="bg-white rounded shadow overflow-hidden hover:shadow-lg flex flex-col justify-between">
                    <img src="../../../public/storage/courses/mket.jpg" alt="Khóa học" class="w-full h-48 object-cover">
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-semibold">Chiến lược kinh doanh thành công</h3>
                        <p class="text-gray-600 text-sm mt-2 flex-grow">Bí quyết để xây dựng doanh nghiệp vững mạnh.</p>
                        <div class="mt-2 flex justify-between">
                            <span class="text-yellow-500 font-semibold">4.7 ⭐</span>
                            <span class="text-purple-600 font-semibold">499.000đ</span>
                        </div>
                    </div>
                </div>
                <!-- Khóa học 3 -->
                <div class="bg-white rounded shadow overflow-hidden hover:shadow-lg flex flex-col justify-between">
                    <img src="../../../public/storage/courses/uiux.jpg" alt="Khóa học" class="w-full h-48 object-cover">
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-semibold">Thiết kế UI/UX chuyên nghiệp</h3>
                        <p class="text-gray-600 text-sm mt-2 flex-grow">Học thiết kế UI/UX theo xu hướng mới nhất.</p>
                        <div class="mt-2 flex justify-between">
                            <span class="text-yellow-500 font-semibold">4.9 ⭐</span>
                            <span class="text-purple-600 font-semibold">399.000đ</span>
                        </div>
                    </div>
                </div>
                <!-- Khóa học 4 -->
                <div class="bg-white rounded shadow overflow-hidden hover:shadow-lg flex flex-col justify-between">
                    <img src="../../../public/storage/courses/mketnew.jpg" alt="Khóa học"
                        class="w-full h-48 object-cover">
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-semibold">Marketing số cho người mới</h3>
                        <p class="text-gray-600 text-sm mt-2 flex-grow">Tìm hiểu cách quảng cáo online hiệu quả.</p>
                        <div class="mt-2 flex justify-between">
                            <span class="text-yellow-500 font-semibold">4.6 ⭐</span>
                            <span class="text-purple-600 font-semibold">350.000đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Section -->
        <div class="container mx-auto px-4 py-6 bg-gray-50 mt-6">
            <h1 class="text-2xl font-semibold mb-4 text-gray-800">Chương trình học tập hướng tới mục tiêu của bạn</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Col 1 -->
                <div class="grid grid-cols-1 gap-4">
                    <!-- Vertical Cards -->
                    <div
                        class="bg-white rounded shadow overflow-hidden hover:bg-purple-100 flex flex-col md:flex-row justify-between">
                        <img src="../../../public/storage/empty-state-1.webp" alt="Image 1"
                            class=" h-20 object-cover self-center">
                        <div class="p-4 flex flex-col flex-grow md:w-10/12">
                            <h1 class="font-semibold text-xl">Những hiểu biết và khoa học phân tích dữ liệu</h1>
                            <p class="text-gray-600 text-sm mt-2 flex-grow">Theo dõi nhanh các mục tiêu bằng những hiểu
                                biết sâu sắc cùng với đội ngũ chăm sóc khách hàng riêng giúp thúc đẩy quá trình học tập
                                hiệu quả.</p>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded shadow overflow-hidden hover:bg-purple-100 flex flex-col md:flex-row justify-between">
                        <img src="../../../public/storage/certificate.webp" alt="Image 2"
                            class="h-20 object-cover self-center">
                        <div class="p-4 flex flex-col flex-grow md:w-10/12">
                            <h3 class="font-semibold">Luyện thi chứng chỉ</h3>
                            <p class="text-gray-600 text-sm mt-2 flex-grow">Luyện thi các chứng chỉ được công nhận trong
                                ngành bằng cách giải quyết các thách thức thực tế và giành được huy hiệu trong quá trình
                                thực hiện.</p>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded shadow overflow-hidden hover:bg-purple-100 flex flex-col md:flex-row justify-between">
                        <img src="../../../public/storage/hands-on-practice.webp" alt="Image 3"
                            class="h-20 object-cover self-center">
                        <div class="p-4 flex flex-col flex-grow md:w-10/12">
                            <h3 class="font-semibold">Đào tạo thực hành</h3>
                            <p class="text-gray-600 text-sm mt-2 flex-grow">Nâng cao kỹ năng một cách hiệu quả với các
                                bài tập coding, bài kiểm tra thực hành, trắc nghiệm và workspace được hỗ trợ bởi AI.
                            </p>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded shadow overflow-hidden hover:bg-purple-100 flex flex-col md:flex-row justify-between">
                        <img src="../../../public/storage/organizations-2.png" alt="Image 4"
                            class="h-20 object-cover self-center">
                        <div class="p-4 flex flex-col flex-grow md:w-10/12">
                            <h3 class="font-semibold">Nội dung có thể tùy chỉnh</h3>
                            <p class="text-gray-600 text-sm mt-2 flex-grow">Tạo lộ trình học tập phù hợp cho các mục
                                tiêu của nhóm và của tổ chức, thậm chí lưu trữ nội dung và tài nguyên của riêng bạn.

                            </p>
                        </div>
                    </div>
                </div>
                <!-- Col 2 -->
                <div class="grid grid-cols-1 ">
                    <div>
                        <img src="../../../public/storage/desktop.png" alt="Large Image"
                            class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>

        <div class="mx-auto px-4 py-6 mt-6">
            <h2 class="text-2xl font-semibold mb-4 text-gray-800">Xem những người khác đang đạt được gì thông qua học
                tập</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Bài đăng 1 -->
                <a href="/khoa-hoc/react-js" class="bg-white shadow-md rounded-lg p-3 hover:shadow-lg transition">
                    <img src="../../../storage/app/public/quote.svg" alt="logoqoute" class="h-4 rounded-md">
                    <p class="text-gray-600 text-sm mt-2">Udemy được đánh giá là <strong> chương trình cấp chứng chỉ
                            hoặc khóa học online phổ biến nhất </strong>về học cách viết code theo Khảo sát nhà phát
                        triển Stack Overflow năm 2023.</p>
                    <p class="text-gray-500 text-xs mt-2">Đăng bởi: User1</p>
                    <div class="flex items-center text-gray-500 text-xs mt-2">
                        <span class="mr-2">👍 150</span>
                        <span>💬 45</span>
                    </div>
                </a>

                <!-- Bài đăng 2 -->
                <a href="/khoa-hoc/react-js" class="bg-white shadow-md rounded-lg p-3 hover:shadow-lg transition">
                    <img src="../../../storage/app/public/quote.svg" alt="logoqoute" class="h-4 rounded-md">
                    <p class="text-gray-600 text-sm mt-2">Udemy được đánh giá là <strong> chương trình cấp chứng chỉ
                            hoặc khóa học online phổ biến nhất </strong>về học cách viết code theo Khảo sát nhà phát
                        triển Stack Overflow năm 2023.</p>
                    <p class="text-gray-500 text-xs mt-2">Đăng bởi: User1</p>
                    <div class="flex items-center text-gray-500 text-xs mt-2">
                        <span class="mr-2">👍 150</span>
                        <span>💬 45</span>
                    </div>
                </a>

                <!-- Bài đăng 3 -->
                <a href="/khoa-hoc/react-js" class="bg-white shadow-md rounded-lg p-3 hover:shadow-lg transition">
                    <img src="../../../storage/app/public/quote.svg" alt="logoqoute" class="h-4 rounded-md">
                    <p class="text-gray-600 text-sm mt-2">Udemy được đánh giá là <strong> chương trình cấp chứng chỉ
                            hoặc khóa học online phổ biến nhất </strong>về học cách viết code theo Khảo sát nhà phát
                        triển Stack Overflow năm 2023.</p>
                    <p class="text-gray-500 text-xs mt-2">Đăng bởi: User1</p>
                    <div class="flex items-center text-gray-500 text-xs mt-2">
                        <span class="mr-2">👍 150</span>
                        <span>💬 45</span>
                    </div>
                </a>

                <!-- Bài đăng 4 -->
                <a href="/khoa-hoc/react-js" class="bg-white shadow-md rounded-lg p-3 hover:shadow-lg transition">
                    <img src="../../../storage/app/public/quote.svg" alt="logoqoute" class="h-4 rounded-md">
                    <p class="text-gray-600 text-sm mt-2">Udemy được đánh giá là <strong> chương trình cấp chứng chỉ
                            hoặc khóa học online phổ biến nhất </strong>về học cách viết code theo Khảo sát nhà phát
                        triển Stack Overflow năm 2023.</p>
                    <p class="text-gray-500 text-xs mt-2">Đăng bởi: User1</p>
                    <div class="flex items-center text-gray-500 text-xs mt-2">
                        <span class="mr-2">👍 150</span>
                        <span>💬 45</span>
                    </div>
                </a>
            </div>
        </div>

       <!-- contact-about-us -->
       <div class="container mx-auto px-4 py-6 bg-gray-50 mt-6">
            <div class="grid grid-cols-12 gap-6">
                <!-- Col 1 -->
                <div class="col-span-4 p-6 self-center">
                    <h1 class="text-3xl font-medium leading-8 text-gray-800">Những xu hướng hàng đầu trong tương lai của việc làm</h1>
                    <span>AI tạo sinh (GenAI) và khả năng lãnh đạo là cốt lõi của nền kinh tế dựa trên kỹ năng ngày nay. Hãy tải Báo cáo Xu hướng kỹ năng và học tập toàn cầu năm 2024 để tìm hiểu thêm.</span>
                </div>
                <!-- Col 2 -->
                <div class="col-span-8">
                    <img src="../../../public/storage/Onsite.webp" class="h-[500px] object-cover" alt="">
                </div>
            </div>
        </div>

    </Container>

    <!-- Chân trang -->
    <footer class="bg-gray-800 text-gray-300">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <h4 class="font-semibold text-white mb-2">Công ty</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white">Về chúng tôi</a></li>
                        <li><a href="#" class="hover:text-white">Nghề nghiệp</a></li>
                        <li><a href="#" class="hover:text-white">Liên hệ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-2">Hỗ trợ</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white">Trung tâm trợ giúp</a></li>
                        <li><a href="#" class="hover:text-white">Câu hỏi thường gặp</a></li>
                        <li><a href="#" class="hover:text-white">Chính sách bảo mật</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-2">Khám phá</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white">Các khóa học</a></li>
                        <li><a href="#" class="hover:text-white">Blog</a></li>
                        <li><a href="#" class="hover:text-white">Sự kiện</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-2">Theo dõi chúng tôi</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-white">Facebook</a></li>
                        <li><a href="#" class="hover:text-white">Twitter</a></li>
                        <li><a href="#" class="hover:text-white">LinkedIn</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-6 pt-6 text-center">
                <p class="text-gray-500">© 2025 Udemy. All rights reserved.</p>
            </div>
        </div>
    </footer>

</template>
