<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{Classes, Section, Session, FeeCategory, Teacher, Student};
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Users
        User::create(['name'=>'প্রধান শিক্ষক','email'=>'admin@school.com','password'=>Hash::make('password')]);
        User::create(['name'=>'হিসাব রক্ষক','email'=>'accounts@school.com','password'=>Hash::make('password')]);

        // Session
        $session = Session::create(['name'=>'2024-2025','start_date'=>'2024-01-01','end_date'=>'2024-12-31','is_current'=>true]);
        Session::create(['name'=>'2025-2026','start_date'=>'2025-01-01','end_date'=>'2025-12-31','is_current'=>false]);

        // School Classes
        $schoolClasses = [
            ['প্রথম শ্রেণী','Class 1',1,'school'],['দ্বিতীয় শ্রেণী','Class 2',2,'school'],
            ['তৃতীয় শ্রেণী','Class 3',3,'school'],['চতুর্থ শ্রেণী','Class 4',4,'school'],
            ['পঞ্চম শ্রেণী','Class 5',5,'school'],['ষষ্ঠ শ্রেণী','Class 6',6,'school'],
            ['সপ্তম শ্রেণী','Class 7',7,'school'],['অষ্টম শ্রেণী','Class 8',8,'school'],
            ['নবম শ্রেণী','Class 9',9,'school'],['দশম শ্রেণী','Class 10',10,'school'],
        ];
        foreach ($schoolClasses as [$name,$nameEn,$num,$type]) {
            $class = Classes::create(['name'=>$name,'name_en'=>$nameEn,'numeric_name'=>$num,'type'=>$type]);
            Section::create(['class_id'=>$class->id,'name'=>'ক (A)']);
            Section::create(['class_id'=>$class->id,'name'=>'খ (B)']);
        }

        // Madrasa Classes
        $madrasaClasses = [
            ['ইবতেদায়ী ১ম শ্রেণী','Ibtedayi 1',1],['ইবতেদায়ী ২য় শ্রেণী','Ibtedayi 2',2],
            ['ইবতেদায়ী ৩য় শ্রেণী','Ibtedayi 3',3],['ইবতেদায়ী ৪র্থ শ্রেণী','Ibtedayi 4',4],
            ['ইবতেদায়ী ৫ম শ্রেণী','Ibtedayi 5',5],['দাখিল ৬ষ্ঠ শ্রেণী','Dakhil 6',6],
            ['দাখিল ৭ম শ্রেণী','Dakhil 7',7],['দাখিল ৮ম শ্রেণী','Dakhil 8',8],
            ['দাখিল ৯ম শ্রেণী','Dakhil 9',9],['দাখিল ১০ম শ্রেণী','Dakhil 10',10],
            ['আলিম ১ম বর্ষ','Alim 1',11],['আলিম ২য় বর্ষ','Alim 2',12],
        ];
        foreach ($madrasaClasses as [$name,$nameEn,$num]) {
            $class = Classes::create(['name'=>$name,'name_en'=>$nameEn,'numeric_name'=>$num,'type'=>'madrasa']);
            Section::create(['class_id'=>$class->id,'name'=>'ক']);
        }

        // Fee Categories
        $fees = [
            ['মাসিক বেতন',500,'monthly','প্রতি মাসের নির্ধারিত বেতন'],
            ['ভর্তি ফি',2000,'once','ভর্তির সময় একবার প্রদেয়'],
            ['পরীক্ষার ফি',300,'yearly','বার্ষিক পরীক্ষার ফি'],
            ['লাইব্রেরি ফি',200,'yearly','বার্ষিক লাইব্রেরি ফি'],
            ['উন্নয়ন তহবিল',500,'yearly','প্রতিষ্ঠান উন্নয়ন ফি'],
            ['বিশেষ পরীক্ষা ফি',150,'once','সাময়িক পরীক্ষার ফি'],
        ];
        foreach ($fees as [$name,$amount,$frequency,$description]) {
            FeeCategory::create(['name'=>$name,'amount'=>$amount,'frequency'=>$frequency,'description'=>$description]);
        }

        // Teachers
        $teachers = [
            ['মোহাম্মদ আব্দুর রহমান','male','এম.এ, বি.এড','প্রধান শিক্ষক',25000],
            ['আয়েশা বেগম','female','বি.এড','সহকারী শিক্ষক',18000],
            ['মোঃ কামাল হোসেন','male','বি.এস.সি','সহকারী শিক্ষক',16000],
            ['ফাতেমা খানম','female','বি.এ, বি.এড','সহকারী শিক্ষক',17000],
            ['মোঃ রফিকুল ইসলাম','male','হিফজ, দাওরা','মাওলানা',18000],
        ];
        foreach ($teachers as $i => [$name,$gender,$qual,$des,$salary]) {
            Teacher::create(['name'=>$name,'gender'=>$gender,'qualification'=>$qual,'designation'=>$des,'salary'=>$salary,'phone'=>'0171100000'.$i,'joining_date'=>now()->subYears(rand(1,5)),'status'=>'active']);
        }

        // Sample Students
        $class6 = Classes::where('name_en','Class 6')->first();
        $sec    = Section::where('class_id',$class6?->id)->first();
        if ($class6 && $sec) {
            $names = [
                ['রাহেলা বেগম','female'],['মোঃ সাকিব হাসান','male'],
                ['তামান্না আক্তার','female'],['আরিফুল ইসলাম','male'],
                ['সুমাইয়া খানম','female'],['নাফিস আহমেদ','male'],
            ];
            foreach ($names as $i => [$name,$gender]) {
                Student::create(['name'=>$name,'gender'=>$gender,'date_of_birth'=>now()->subYears(12)->subDays($i*30),'father_name'=>'মোঃ আব্দুল করিম','mother_name'=>'বেগম সাহেবা','guardian_phone'=>'0171200000'.$i,'address'=>'গ্রাম: উদাহরণপুর, জেলা: ঢাকা','class_id'=>$class6->id,'section_id'=>$sec->id,'session_id'=>$session->id,'admission_date'=>now()->subMonths(3),'roll_number'=>$i+1,'status'=>'active']);
            }
        }

        $this->command->info('✅ Seeding সম্পন্ন! Login: admin@school.com / password');
    }
}
