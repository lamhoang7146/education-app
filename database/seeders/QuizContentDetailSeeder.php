<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuizContentDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('quiz_content_details')->insert([
            'question' => 'Python là ngôn ngữ lập trình kiểu gì?',
            'answers' => 'Biên dịch,Thông dịch,Hợp ngữ,Máy',
            'result' => 'Thông dịch',
            'quiz_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Làm sao để kiểm tra kiểu dữ liệu của một biến?',
            'answers' => 'typeof(x),x.type,checkType(x),type(x)',
            'result' => 'type(x)',
            'quiz_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Biến nào là hợp lệ trong Python?',
            'answers' => '2name,_name,my-name,class',
            'result' => '_name',
            'quiz_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Toán tử // trong Python có nghĩa là gì?',
            'answers' => 'Phép nhân, Phép chia lấy nguyên, Phép chia lấy dư, Phép lũy thừa',
            'result' => 'Phép chia lấy nguyên',
            'quiz_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'None trong Python có nghĩa là gì?',
            'answers' => 'Giá trị rỗng,Giá trị false,Số 0,Biến chưa khai báo',
            'result' => 'Giá trị rỗng',
            'quiz_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Đâu là kiểu dữ liệu không thay đổi được?',
            'answers' => 'List,Set,Dictionary,Tuple',
            'result' => 'Tuple',
            'quiz_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Đâu là kiểu dữ liệu không thay đổi được?',
            'answers' => 'List,Set,Dictionary,Tuple',
            'result' => 'Tuple',
            'quiz_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Kiểu dữ liệu nào là bất biến trong Python?',
            'answers' => 'List,Set,Dictionary,Tuple',
            'result' => 'Tuple',
            'quiz_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Làm sao để thêm phần tử vào danh sách my_list?',
            'answers' => 'my_list.add(5),my_list.append(5),my_list.push(5),my_list.insert(5)',
            'result' => 'my_list.append(5)',
            'quiz_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Cách nào để lấy phần tử cuối cùng của danh sách my_list?',
            'answers' => 'my_list[-1],my_list.last(),my_list[len(my_list)],my_list.popLast()',
            'result' => 'my_list[-1]',
            'quiz_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Tập hợp (set) trong Python có đặc điểm gì?',
            'answers' => 'Chứa các phần tử trùng lặp,Các phần tử có thứ tự cố định,Không cho phép trùng lặp,Không thể thay đổi',
            'result' => 'Không cho phép trùng lặp',
            'quiz_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Phương thức nào được dùng để xóa phần tử trong danh sách?',
            'answers' => 'remove(),delete(),pop(),discard()',
            'result' => 'remove()',
            'quiz_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Để duyệt từng phần tử trong danh sách my_list, dùng cú pháp nào?',
            'answers' => 'for item in my_list,my_list.forEach(),loop my_list,while i < len(my_list)',
            'result' => 'for item in my_list',
            'quiz_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Dictionary có thể chứa key nào?',
            'answers' => 'Chỉ số nguyên,Chỉ chuỗi,Bất kỳ kiểu dữ liệu nào,Chỉ tuple',
            'result' => 'Bất kỳ kiểu dữ liệu nào',
            'quiz_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Khi muốn sao chép danh sách mà không ảnh hưởng đến bản gốc, dùng cách nào?',
            'answers' => 'new_list = my_list,new_list = my_list.copy(),new_list = list(my_list),new_list = deepcopy(my_list)',
            'result' => 'new_list = my_list.copy()',
            'quiz_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Hàm len() trả về gì?',
            'answers' => 'Độ dài danh sách,Giá trị lớn nhất,Giá trị nhỏ nhất,Tổng các phần tử',
            'result' => 'Độ dài danh sách',
            'quiz_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Hàm len() trả về gì?',
            'answers' => 'Độ dài danh sách,Giá trị lớn nhất,Giá trị nhỏ nhất,Tổng các phần tử',
            'result' => 'Độ dài danh sách',
            'quiz_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'def dùng để làm gì trong Python?',
            'answers' => 'Định nghĩa biến,Định nghĩa danh sách,Định nghĩa hàm,Định nghĩa lớp',
            'result' => 'Định nghĩa hàm',
            'quiz_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'return trong một hàm dùng để làm gì?',
            'answers' => 'Kết thúc vòng lặp,Trả về giá trị từ hàm,Gán giá trị cho biến,Xuất dữ liệu ra màn hình',
            'result' => 'Trả về giá trị từ hàm',
            'quiz_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Dòng lệnh nào tạo một vòng lặp chạy 5 lần?',
            'answers' => 'for i in range(5),while i < 5,loop(5),repeat 5 times',
            'result' => 'for i in range(5)',
            'quiz_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Làm sao để viết một hàm có tham số mặc định?',
            'answers' => 'def my_func(x = 10):,function my_func(x = 10):,def my_func(x: 10):,def my_func(x -> 10):',
            'result' => 'def my_func(x = 10):',
            'quiz_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Từ khóa nào dùng để dừng vòng lặp sớm?',
            'answers' => 'continue,stop,exit,break',
            'result' => 'break',
            'quiz_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Nếu một hàm không có câu lệnh return, nó trả về gì?',
            'answers' => 'None,0,False,Lỗi',
            'result' => 'None',
            'quiz_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'Làm sao để gọi hàm greet()?',
            'answers' => 'call greet(), greet(), run greet(), greet.run()',
            'result' => 'greet()',
            'quiz_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('quiz_content_details')->insert([
            'question' => 'pass trong Python có tác dụng gì?',
            'answers' => 'Bỏ qua lỗi, Tiếp tục vòng lặp, Giữ chỗ trong code, Dừng chương trình',
            'result' => 'Giữ chỗ trong code',
            'quiz_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
