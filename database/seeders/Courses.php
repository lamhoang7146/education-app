<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Courses extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('courses')->insert([
            'title' => 'Làm chủ Kỹ năng Lập trình Python từ Cơ bản đến Nâng cao',
            'description' => '<h3><strong>Mô tả khóa học: </strong></h3><p>Khóa học này dành cho những ai muốn bắt đầu hành trình trở thành một lập trình viên Python chuyên nghiệp. Bạn sẽ được học từ những kiến thức cơ bản nhất như cú pháp, biến, vòng lặp, đến các chủ đề nâng cao như lập trình hướng đối tượng, xử lý dữ liệu với thư viện Pandas, và tự động hóa các tác vụ với Python. Khóa học phù hợp cho cả người mới bắt đầu và những người muốn nâng cao kỹ năng hiện có.</p><h3><strong>Mô tả chi tiết khóa học:</strong></h3><ol><li><p><strong>Giới thiệu về Python:</strong></p><ul><li><p>Tổng quan về ngôn ngữ Python và ứng dụng trong thực tế.</p></li><li><p>Cài đặt môi trường và công cụ lập trình.</p></li></ul></li><li><p><strong>Cú pháp cơ bản:</strong></p><ul><li><p>Biến, kiểu dữ liệu, và toán tử.</p></li><li><p>Cấu trúc điều khiển: if-else, vòng lặp for và while.</p></li></ul></li><li><p><strong>Hàm và Module:</strong></p><ul><li><p>Cách tạo và sử dụng hàm.</p></li><li><p>Import và sử dụng các module có sẵn.</p></li></ul></li><li><p><strong>Lập trình hướng đối tượng (OOP):</strong></p><ul><li><p>Class, Object, Inheritance, và Polymorphism.</p></li><li><p>Ứng dụng OOP trong các dự án thực tế.</p></li></ul></li><li><p><strong>Xử lý dữ liệu với Pandas:</strong></p><ul><li><p>Làm việc với DataFrame và Series.</p></li><li><p>Phân tích và xử lý dữ liệu từ các nguồn khác nhau.</p></li></ul></li><li><p><strong>Tự động hóa với Python:</strong></p><ul><li><p>Viết script tự động hóa tác vụ hàng ngày.</p></li><li><p>Làm việc với file và thư mục.</p></li></ul></li><li><p><strong>Dự án cuối khóa:</strong></p><ul><li><p>Áp dụng kiến thức đã học để xây dựng một ứng dụng hoàn chỉnh.</p></li><li><p>Nhận phản hồi và hướng dẫn từ giảng viên.</p></li></ul></li></ol>',
            'thumbnail' => 'courses/courses-1.jpg',
            'is_free' => false,
            'price' => 500000,
            'category_courses_id' => 3,
            'level' => 'Medium',
            'user_id' => 1,
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('courses')->insert([
            'title' => 'Lập trình Web Fullstack với React và Node.js - Từ Zero đến Hero',
            'description' => '<h3><strong>Mô tả khóa học:</strong></h3><p>Khóa học này dành cho những ai muốn trở thành một lập trình viên Fullstack chuyên nghiệp. Bạn sẽ học cách xây dựng ứng dụng web hoàn chỉnh từ frontend đến backend, sử dụng React cho giao diện người dùng và Node.js cho server. Khóa học bao gồm cả lý thuyết và thực hành, giúp bạn nắm vững kiến thức và kỹ năng cần thiết để tự tin ứng tuyển vào các vị trí Fullstack Developer.</p><h3><strong>Mô tả chi tiết khóa học:</strong></h3><h4><strong>1. Giới thiệu tổng quan:</strong></h4><ul><li><p>Tìm hiểu về lập trình Fullstack và các công nghệ được sử dụng trong khóa học.</p></li><li><p>Cài đặt môi trường phát triển: Node.js, npm, React, và các công cụ hỗ trợ.</p></li></ul><h4><strong>2. Frontend với React:</strong></h4><ul><li><p>Cơ bản về React: Components, Props, State, và JSX.</p></li><li><p>Xử lý sự kiện và điều hướng với React Router.</p></li><li><p>Quản lý state với React Hooks và Redux.</p></li><li><p>Tích hợp API vào ứng dụng React.</p></li></ul><h4><strong>3. Backend với Node.js và Express:</strong></h4><ul><li><p>Tạo server cơ bản với Node.js và Express.</p></li><li><p>Kết nối cơ sở dữ liệu MongoDB và thao tác với dữ liệu.</p></li><li><p>Xây dựng RESTful API cho ứng dụng web.</p></li><li><p>Bảo mật API với JWT (JSON Web Token).</p></li></ul><h4><strong>4. Kết nối Frontend và Backend:</strong></h4><ul><li><p>Gửi và nhận dữ liệu giữa React và Node.js.</p></li><li><p>Xử lý lỗi và hiển thị thông báo cho người dùng.</p></li><li><p>Triển khai ứng dụng lên các nền tảng như Heroku, Vercel, hoặc AWS.</p></li></ul><h4><strong>5. Dự án thực tế:</strong></h4><ul><li><p>Xây dựng một ứng dụng web hoàn chỉnh: từ thiết kế, phát triển, đến triển khai.</p></li><li><p>Áp dụng kiến thức đã học vào một sản phẩm thực tế.</p></li><li><p>Nhận phản hồi và hướng dẫn từ giảng viên.</p></li></ul><h4><strong>6. Kết thúc khóa học:</strong></h4><ul><li><p>Tổng hợp kiến thức và chuẩn bị cho các kỳ thi chứng chỉ hoặc phỏng vấn xin việc.</p></li><li><p>Tài liệu tham khảo và các nguồn học tập tiếp theo.</p></li></ul>',
            'thumbnail' => 'courses/courses-2.jpg',
            'is_free' => true,
            'price' => null,
            'category_courses_id' => 1,
            'level' => 'Easy',
            'user_id' => 1,
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('courses')->insert([
            'title' => 'Phân tích Dữ liệu với Python và Pandas - Từ Cơ bản đến Chuyên sâu',
            'description' => '<h3><strong>Mô tả khóa học:</strong></h3><p>Khóa học này dành cho những ai muốn khám phá sức mạnh của phân tích dữ liệu bằng Python. Bạn sẽ học cách sử dụng thư viện Pandas để làm sạch, phân tích, và trực quan hóa dữ liệu một cách hiệu quả. Khóa học cung cấp kiến thức từ cơ bản đến nâng cao, giúp bạn trở thành một chuyên gia phân tích dữ liệu trong thời gian ngắn.</p><h3><strong>Mô tả chi tiết khóa học:</strong></h3><h4><strong>1. Giới thiệu về Phân tích Dữ liệu:</strong></h4><ul><li><p>Tổng quan về phân tích dữ liệu và ứng dụng trong thực tế.</p></li><li><p>Cài đặt môi trường làm việc: Python, Jupyter Notebook, và các thư viện cần thiết.</p></li></ul><h4><strong>2. Làm quen với Pandas:</strong></h4><ul><li><p>Cấu trúc dữ liệu cơ bản: Series và DataFrame.</p></li><li><p>Đọc và ghi dữ liệu từ các nguồn khác nhau (CSV, Excel, SQL, v.v.).</p></li><li><p>Thao tác cơ bản với DataFrame: lọc, sắp xếp, và truy vấn dữ liệu.</p></li></ul><h4><strong>3. Làm sạch Dữ liệu:</strong></h4><ul><li><p>Xử lý dữ liệu thiếu (missing data).</p></li><li><p>Loại bỏ dữ liệu trùng lặp và không cần thiết.</p></li><li><p>Chuyển đổi và chuẩn hóa dữ liệu.</p></li></ul><h4><strong>4. Phân tích Dữ liệu:</strong></h4><ul><li><p>Tính toán các thống kê cơ bản: mean, median, mode, standard deviation.</p></li><li><p>Phân tích dữ liệu theo nhóm (groupby) và tạo bảng tổng hợp (pivot table).</p></li><li><p>Kết hợp và hợp nhất dữ liệu từ nhiều nguồn.</p></li></ul><h4><strong>5. Trực quan hóa Dữ liệu:</strong></h4><ul><li><p>Sử dụng Matplotlib và Seaborn để vẽ biểu đồ.</p></li><li><p>Tạo các biểu đồ phổ biến: line chart, bar chart, histogram, scatter plot.</p></li><li><p>Trực quan hóa dữ liệu đa chiều với heatmap và pairplot.</p></li></ul><h4><strong>6. Dự án Thực tế:</strong></h4><ul><li><p>Phân tích một bộ dữ liệu thực tế từ đầu đến cuối.</p></li><li><p>Trình bày kết quả phân tích và đưa ra các đề xuất dựa trên dữ liệu.</p></li><li><p>Nhận phản hồi và hướng dẫn từ giảng viên.</p></li></ul><h4><strong>7. Kết thúc Khóa học:</strong></h4><ul><li><p>Tổng hợp kiến thức và chuẩn bị cho các kỳ thi chứng chỉ hoặc phỏng vấn xin việc.</p></li><li><p>Tài liệu tham khảo và các nguồn học tập tiếp theo.</p></li></ul>',
            'thumbnail' => 'courses/courses-3.jpg',
            'is_free' => false,
            'price' => 2500000,
            'category_courses_id' => 2,
            'level' => 'Extremely',
            'user_id' => 1,
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
