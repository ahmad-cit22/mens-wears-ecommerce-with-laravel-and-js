<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\District;
use Illuminate\Http\Request;
use Auth;
use Alert;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('area.index')) {
            $areas = Area::orderBy('id', 'DESC')->get();
            $districts = District::orderBy('id', 'DESC')->get();
            return view('admin.area.index', compact('areas', 'districts'));
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }
    public function upload()
    {
        $districts = array (
            'Bagerhat' => array(
                        'Bagerhat Sadar',
                        'Chitalmari',
                        'Fakirhat',
                        'Kachua',
                        'Mollahat',
                        'Mongla',
                        'Morrelganj',
                        'Rampal',
                        'Sarankhola',
                    ),
            'Bandarban' => array(
                        'Ali Kadam',
                        'Bandarban Sadar',
                        'Lama',
                        'Naikhongchhari',
                        'Rowangchhari',
                        'Ruma',
                        'Thanchi',
                    ),
            'Barguna' => array(
                        'Amtali',
                        'Bamna',
                        'Barguna Sadar',
                        'Betagi',
                        'Patharghata',
                        'Taltali',
                    ),
            'Barisal' => array(
                        'Agailjhara',
                        'Babuganj',
                        'Bakerganj',
                        'Banaripara',
                        'Gaurnadi',
                        'Hizla',
                        'Barishal Sadar',
                        'Mehendiganj',
                        'Muladi',
                        'Wazirpur',
                    ),
            'Bhola' => array(
                        'Bhola Sadar',
                        'Burhanuddin',
                        'Char Fasson',
                        'Daulatkhan',
                        'Lalmohan',
                        'Manpura',
                        'Tazumuddin',
                    ),
            'Bogura' => array(
                        'Adamdighi',
                        'Bogura Sadar',
                        'Dhunat',
                        'Dhupchanchia',
                        'Gabtali',
                        'Kahaloo',
                        'Nandigram',
                        'Sariakandi',
                        'Shajahanpur',
                        'Sherpur',
                        'Shibganj',
                        'Sonatola',
                    ),
            'Brahmanbaria' => array(
                        'Akhaura',
                        'Bancharampur',
                        'Brahmanbaria Sadar',
                        'Kasba',
                        'Nabinagar',
                        'Nasirnagar',
                        'Sarail',
                        'Ashuganj',
                        'Bijoynagar',
                    ),
            'Chandpur' => array(
                        'Chandpur Sadar',
                        'Faridganj',
                        'Haimchar',
                        'Haziganj',
                        'Kachua',
                        'Matlab Dakshin',
                        'Matlab Uttar',
                        'Shahrasti',
                    ),
            'Chapainawabganj' => array(
                        'Bholahat',
                        'Gomastapur',
                        'Nachole',
                        'Nawabganj Sadar',
                        'Shibganj',
                    ),
            'Chattogram' => array(
                        'Anwara',
                        'Banshkhali',
                        'Boalkhali',
                        'Chandanaish',
                        'Fatikchhari',
                        'Hathazari',
                        'Karnaphuli',
                        'Lohagara',
                        'Mirsharai',
                        'Patiya',
                        'Rangunia',
                        'Raozan',
                        'Sandwip',
                        'Satkania',
                        'Sitakunda',
                        'Bandar Thana',
                        'Chandgaon Thana',
                        'Double Mooring Thana',
                        'Kotwali Thana',
                        'Pahartali Thana',
                        'Panchlaish Thana',
                        'Bhujpur Thana',
                    ),
            'Chuadanga' => array(
                        'Alamdanga',
                        'Chuadanga Sadar',
                        'Damurhuda',
                        'Jibannagar',

                    ),
            'Cox\'s Bazar' => array(
                        'Chakaria',
                        'Cox\'s Bazar Sadar',
                        'Kutubdia',
                        'Maheshkhali',
                        'Ramu',
                        'Teknaf',
                        'Ukhia',
                        'Pekua',
                    ),
            'Cumilla' => array(
                        'Barura',
                        'Brahmanpara',
                        'Burichang',
                        'Chandina',
                        'Chauddagram',
                        'Daudkandi',
                        'Debidwar',
                        'Homna',
                        'Laksam',
                        'Muradnagar',
                        'Nangalkot',
                        'Cumilla Adarsha Sadar',
                        'Meghna',
                        'Titas',
                        'Monohargonj',
                        'Cumilla Sadar Dakshin',
                    ),
            'Dhaka' => array(
                        'Adabor',
                        'Airport',
                        'Badda',
                        'Banani',
                        'Bangshal',
                        'Bhashantek',
                        'Cantonment',
                        'Chackbazar',
                        'Dakshin Khan',
                        'Darus-Salam',
                        'Demra',
                        'Dhamrai',
                        'Dhanmondi',
                        'Dohar',
                        'Gandaria',
                        'Gulshan',
                        'Hatirjheel',
                        'Hazaribhag',
                        'Jattrabari',
                        'Kadamtoli',
                        'Kafrul',
                        'Kalabagan',
                        'Kamrangir Char',
                        'Keraniganj Model',
                        'Khilgaon',
                        'Khilkhet',
                        'Kotwali',
                        'Lalbag',
                        'Mirpur Model',
                        'Mohammadpur',
                        'Motijheel',
                        'Mugda',
                        'Nawabganj',
                        'New Market',
                        'Pallabi',
                        'Paltan Model',
                        'Ramna Model',
                        'Rampura',
                        'Rupnagar',
                        'Sabujbhag',
                        'Savar',
                        'Shah Ali',
                        'Shahbag',
                        'Shahjahanpur',
                        'Sher e Bangla Nagar',
                        'Shyampur',
                        'South Keraniganj',
                        'Sutrapur',
                        'Tejgaon',
                        'Tejgaon Industrial',
                        'Turag',
                        'Uttar Khan',
                        'Uttara East',
                        'Uttara West',
                        'WariVatara',
                    ),
            'Dinajpur' => array(
                        'Birampur',
                        'Birganj',
                        'Biral',
                        'Bochaganj',
                        'Chirirbandar',
                        'Phulbari',
                        'Ghoraghat',
                        'Hakimpur',
                        'Kaharole',
                        'Khansama',
                        'Dinajpur Sadar',
                        'Nawabganj',
                        'Parbatipur',
                    ),
            'Faridpur' => array(
                        'Alfadanga',
                        'Bhanga',
                        'Boalmari',
                        'Charbhadrasan',
                        'Faridpur Sadar',
                        'Madhukhali',
                        'Nagarkanda',
                        'Sadarpur',
                        'Saltha',
                    ),
            'Feni' => array(
                        'Chhagalnaiya',
                        'Daganbhuiyan',
                        'Feni Sadar',
                        'Parshuram',
                        'Sonagazi',
                        'Fulgazi',
                    ),
            'Gaibandha' => array(
                        'Phulchhari',
                        'Gaibandha Sadar',
                        'Gobindaganj',
                        'Palashbari',
                        'Sadullapur',
                        'Sughatta',
                        'Sundarganj',
                    ),
            'Gazipur' => array(
                        'Gazipur Sadar',
                        'Kaliakair',
                        'Kaliganj',
                        'Kapasia',
                        'Sreepur',
                    ),
            'Gopalganj' => array(
                        'Gopalganj Sadar',
                        'Kashiani',
                        'Kotalipara',
                        'Muksudpur',
                        'Tungipara',
                    ),
            'Habiganj' => array(
                        'Ajmiriganj',
                        'Bahubal',
                        'Baniyachong',
                        'Chunarughat',
                        'Habiganj Sadar',
                        'Lakhai',
                        'Madhabpur',
                        'Nabiganj',
                        'Sayestaganj',
                    ),
            'Jaipurhat' => array(
                        'Akkelpur',
                        'Jaipurhat Sadar',
                        'Kalai',
                        'Khetlal',
                        'Panchbibi',
                    ),
            'Jamalpur' => array(
                        'Baksiganj',
                        'Dewanganj',
                        'Islampur',
                        'Jamalpur Sadar',
                        'Madarganj',
                        'Melandaha',
                        'Sarishabari',
                    ),
            'Jashore' => array(
                        'Abhaynagar',
                        'Bagherpara',
                        'Chaugachha',
                        'Jhikargachha',
                        'Keshabpur',
                        'Jashore Sadar',
                        'Manirampur',
                        'Sharsha',
                    ),
            'Jhalokati' => array(
                        'Jhalokati Sadar',
                        'Kathalia',
                        'Nalchity',
                        'Rajapur',
                    ),
            'Jhenaidah' => array(
                        'Harinakunda',
                        'Jhenaidah Sadar',
                        'Kaliganj',
                        'Kotchandpur',
                        'Maheshpur',
                        'Shailkupa',
                    ),
            'Khagrachhari' => array(
                        'Dighinala',
                        'Khagrachhari',
                        'Lakshmichhari',
                        'Mahalchhari',
                        'Manikchhari',
                        'Matiranga',
                        'Panchhari',
                        'Ramgarh',
                    ),
            'Khulna' => array(
                        'Batiaghata',
                        'Dacope',
                        'Dumuria',
                        'Dighalia',
                        'Koyra',
                        'Paikgachha',
                        'Phultala',
                        'Rupsha',
                        'Terokhada',
                        'Daulatpur Thana',
                        'Khalishpur Thana',
                        'Khan Jahan Ali Thana',
                        'Kotwali Thana',
                        'Sonadanga Thana',
                        'Harintana Thana',
                    ),
            'Kishoreganj' => array(
                        'Austagram',
                        'Bajitpur',
                        'Bhairab',
                        'Hossainpur',
                        'Itna',
                        'Karimganj',
                        'Katiadi',
                        'Kishoreganj Sadar',
                        'Kuliarchar',
                        'Mithamain',
                        'Nikli',
                        'Pakundia',
                    ),
            'Kurigram' => array(
                        'Bhurungamari',
                        'Char Rajibpur',
                        'Chilmari',
                        'Phulbari',
                        'Kurigram Sadar',
                        'Nageshwari',
                        'Rajarhat',
                        'Raomari',
                        'Ulipur',
                    ),
            'Kushtia' => array(
                        'Bheramara',
                        'Daulatpur',
                        'Khoksa',
                        'Kumarkhali',
                        'Kushtia Sadar',
                        'Mirpur',
                    ),
            'Lalmonirhat' => array(
                        'Aditmari',
                        'Hatibandha',
                        'Kaliganj',
                        'Lalmonirhat Sadar',
                        'Patgram',
                    ),
            'Lakshmipur' => array(
                        'Lakshmipur Sadar',
                        'Raipur',
                        'Ramganj',
                        'Ramgati',
                        'Kamalnagar',
                    ),
            'Madaripur' => array(
                        'Rajoir',
                        'Madaripur Sadar',
                        'Kalkini',
                        'Shibchar',
                    ),
            'Magura' => array(
                        'Magura Sadar',
                        'Mohammadpur',
                        'Shalikha',
                        'Sreepur',
                    ),
            'Manikganj' => array(
                        'Daulatpur',
                        'Ghior',
                        'Harirampur',
                        'Manikgonj Sadar',
                        'Saturia',
                        'Shivalaya',
                        'Singair',
                    ),
            'Meherpur' => array(
                        'Gangni',
                        'Meherpur Sadar',
                        'Mujibnagar',
                    ),
            'Mauluvibazar' => array(
                        'Barlekha',
                        'Juri',
                        'Kamalganj',
                        'Kulaura',
                        'Moulvibazar Sadar',
                        'Rajnagar',
                        'Sreemangal',
                    ),
            'Munshiganj' => array(
                        'Gazaria',
                        'Lohajang',
                        'Munshiganj Sadar',
                        'Sirajdikhan',
                        'Sreenagar',
                        'Tongibari',
                    ),
            'Mymensingh' => array(
                        'Trishal',
                        'Dhobaura',
                        'Fulbaria',
                        'Gaffargaon',
                        'Gauripur',
                        'Haluaghat',
                        'Ishwarganj',
                        'Mymensingh Sadar',
                        'Muktagachha',
                        'Nandail',
                        'Phulpur',
                        'Bhaluka',
                        'Tara Khanda',
                    ),
            'Naogaon' => array(
                        'Atrai',
                        'Badalgachhi',
                        'Manda',
                        'Dhamoirhat',
                        'Mohadevpur',
                        'Naogaon Sadar',
                        'Niamatpur',
                        'Patnitala',
                        'Porsha',
                        'Raninagar',
                        'Sapahar',
                    ),
            'Narail' => array(
                        'Kalia',
                        'Lohagara',
                        'Narail Sadar',
                        'Naragati Thana',
                    ),
            'Narayanganj' => array(
                        'Araihazar',
                        'Bandar',
                        'Narayanganj Sadar',
                        'Rupganj',
                        'Sonargaon',
                    ),
            'Narsingdi' => array(
                        'Narsingdi Sadar',
                        'Belabo',
                        'Monohardi',
                        'Palash',
                        'Raipura',
                        'Shibpur',
                    ),
            'Natore' => array(
                        'Bagatipara',
                        'Baraigram',
                        'Gurudaspur',
                        'Lalpur',
                        'Natore Sadar',
                        'Singra',
                        'Naldanga',
                    ),
            'Netrokona' => array(
                        'Atpara',
                        'Barhatta',
                        'Durgapur',
                        'Khaliajuri',
                        'Kalmakanda',
                        'Kendua',
                        'Madan',
                        'Mohanganj',
                        'Netrokona Sadar',
                        'Purbadhala',
                    ),
            'Nilphamari' => array(
                        'Dimla',
                        'Domar',
                        'Jaldhaka',
                        'Kishoreganj',
                        'Nilphamari Sadar',
                        'Saidpur',
                    ),
            'Noakhali' => array(
                        'Begumganj',
                        'Noakhali Sadar',
                        'Chatkhil',
                        'Companiganj',
                        'Hatiya',
                        'Senbagh',
                        'Sonaimuri',
                        'Subarnachar',
                        'Kabirhat',
                    ),
            'Pabna' => array(
                        'Atgharia',
                        'Bera',
                        'Bhangura',
                        'Chatmohar',
                        'Faridpur',
                        'Ishwardi',
                        'Pabna Sadar',
                        'Santhia',
                        'Sujanagar',
                    ),
            'Panchagarh' => array(
                        'Atwari',
                        'Boda',
                        'Debiganj',
                        'Panchagarh Sadar',
                        'Tetulia',
                    ),
            'Patuakhali' => array(
                        'Bauphal',
                        'Dashmina',
                        'Galachipa',
                        'Kalapara',
                        'Mirzaganj',
                        'Patuakhali Sadar',
                        'Rangabali',
                        'Dumki',
                    ),
            'Pirojpur' => array(
                        'Bhandaria',
                        'Kawkhali',
                        'Mathbaria',
                        'Nazirpur',
                        'Pirojpur Sadar',
                        'Nesarabad (Swarupkati)',
                        'Zianagar',
                    ),
            'Rajbari' => array(
                        'Baliakandi',
                        'Goalandaghat',
                        'Pangsha',
                        'Rajbari Sadar',
                        'Kalukhali',
                    ),
            'Rajshahi' => array(
                        'Bagha',
                        'Bagmara',
                        'Charghat',
                        'Durgapur',
                        'Godagari',
                        'Mohanpur',
                        'Paba',
                        'Puthia',
                        'Tanore',
                    ),
            'Rangamati' => array(
                        'Bagaichhari',
                        'Barkal',
                        'Kawkhali (Betbunia)',
                        'Belaichhari',
                        'Kaptai',
                        'Juraichhari',
                        'Langadu',
                        'Naniyachar',
                        'Rajasthali',
                        'Rangamati Sadar',
                    ),
            'Rangpur' => array(
                        'Badarganj',
                        'Gangachhara',
                        'Kaunia',
                        'Rangpur Sadar',
                        'Mithapukur',
                        'Pirgachha',
                        'Pirganj',
                        'Taraganj',
                    ),
            'Satkhira' => array(
                        'Assasuni',
                        'Debhata',
                        'Kalaroa',
                        'Kaliganj',
                        'Satkhira Sadar',
                        'Shyamnagar',
                        'Tala',
                    ),
            'Shariatpur' => array(
                        'Bhedarganj',
                        'Damudya',
                        'Gosairhat',
                        'Naria',
                        'Shariatpur Sadar',
                        'Zajira',
                        'Shakhipur',
                    ),
            'Sherpur' => array(
                        'Jhenaigati',
                        'Nakla',
                        'Nalitabari',
                        'Sherpur Sadar',
                        'Sreebardi',
                    ),
            'Sirajganj' => array(
                        'Belkuchi',
                        'Chauhali',
                        'Kamarkhanda',
                        'Kazipur',
                        'Raiganj',
                        'Shahjadpur',
                        'Sirajganj Sadar',
                        'Tarash',
                        'Ullahpara',
                    ),
            'Sunamganj' => array(
                        'Bishwamvarpur',
                        'Chhatak',
                        'Dakshin Sunamganj',
                        'Derai',
                        'Dharamapasha',
                        'Dowarabazar',
                        'Jagannathpur',
                        'Jamalganj',
                        'Sullah',
                        'Sunamganj Sadar',
                        'Tahirpur',
                    ),
            'Sylhet' => array(
                        'Balaganj',
                        'Beanibazar',
                        'Bishwanath',
                        'Companigonj',
                        'Dakshin Surma',
                        'Fenchuganj',
                        'Golapganj',
                        'Gowainghat',
                        'Jaintiapur',
                        'Kanaighat',
                        'Osmani Nagar',
                        'Sylhet Sadar',
                        'Zakiganj',
                    ),
            'Tangail' => array(
                        'Gopalpur',
                        'Basail',
                        'Bhuapur',
                        'Delduar',
                        'Ghatail',
                        'Kalihati',
                        'Madhupur',
                        'Mirzapur',
                        'Nagarpur',
                        'Sakhipur',
                        'Dhanbari',
                        'Tangail Sadar',
                    ),
            'Thakurgaon' => array(
                        'Baliadangi',
                        'Haripur',
                        'Pirganj',
                        'Ranisankail',
                        'Thakurgaon Sadar',
                    ),
            // Sorting End

        );
        //dd($districts);
        $i = 1;
        foreach ($districts as $sub_districts) {
            foreach ($sub_districts as $sub_district) {
                $area = new Area;
                $area->name = $sub_district;
                $area->district_id = $i;
                $area->save();
            }
            $i += 1;
        }
        Alert::toast('Area Uploaded!', 'success');
        return back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->can('area.create')) {
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'district_id' => 'required|integer',
            ]);
            $area = new Area;
            $area->name = $request->name;
            $area->district_id = $request->district_id;
            $area->location = $request->location;
            $area->save();
            Alert::toast('One Area Added !', 'success');
            return redirect()->route('area.index');
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function show(Area $area)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $area)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->can('area.edit')) {
            $area = Area::find($id);
            if (!is_null($area)) {
                $this->validate($request, [
                    'name' => 'required',
                ]);

                $area->name = $request->name;
                $area->location = $request->location;

                $area->save();
                Alert::toast('Area has been updated !', 'success');
                return redirect()->route('area.index');
                
            }
            else {
                Alert::toast('Area Not Found !', 'warning');
                return back();
            }
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->can('area.delete')) {
            $area = Area::find($id);
            if (!is_null($area)) {
                
                $area->delete();
                Alert::toast('Area has been deleted !', 'success');
                return back();
            }
            else {
                Alert::toast('Area Not Found !', 'warning');
                return back();
            }
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }
    }
}
