<template>
    <div class="inquiry-page">

        <b-modal 
            ref="change-signature"
            scrollable 
            no-close-on-esc
            no-close-on-backdrop
            header-class="border-0 bg-warning text-white modal-head"
            centered 
            title="Change Signature"
            size="md"
        >

            <template #modal-header-close>
                <i class="fa-solid fa-xmark"></i>
            </template>

            <div class="signature-form">
                <!-- <div></div> -->
                <div class="mb-3">
                    <label for="formFile" class="form-label">Your Signtaure</label>
                    <input class="form-control" type="file" ref="formFile" @change="triggerSignature()">
                </div>
            </div>

            <template #modal-footer>
                <button class="btn btn-warning " @click="changeSignature()"> Change</button>
            </template>
        </b-modal>

        <!-- Loading Modal -->
            <b-modal 
                ref="loading-modal"
                scrollable 
                no-close-on-esc
                no-close-on-backdrop
                hide-header-close
                header-class="border-0 text-dark-green"
                hide-footer 
                centered 
            >
                <template #modal-title> 
                    <div>
                        <h4> <span  style="margin: 0px 1rem 0px 0px;"> Loading please wait </span> <i class="fa-solid fa-spinner fa-spin"></i> </h4>
                    </div>
                </template>

                <div class="d-flex justify-content-center">
                    <img src="/img/Loading.gif" alt="loading">
                </div>

                <template #modal-footer> 
                    <div></div>
                </template>

            </b-modal>
        <!-- END -->
            
        <b-modal 
            ref="preview-modal"
            scrollable 
            no-close-on-esc
            no-close-on-backdrop
            header-class="border-0 bg-dark-green text-white modal-head"
            centered 
            :title="isPrescribed ? 'Preview Prescription' : 'Laboratory Request'"
            size="xl"
        >

            <template #modal-header-close>
                <i class="fa-solid fa-xmark"></i>
            </template>

            <div class="preview-details">

                <div v-if="isPrescribed" class="prescribe-form">

                    <div class="header">
                        <img src="/img/logo1.png" alt="Logo" width="90" height="115">

                        <div class="doctor-details">
                            <div class="img"
                            :style="{backgroundImage: 'url(' + (user.photoUrl == '' || user.photoUrl == null ? '/img/avatar.png' : user.photoUrl) + ')',}"
                            ></div>
                            <h4 class="mb-0 fw-bold"> Dr. {{user.name}}</h4>
                        </div>
                        <h6 class="fw-normal">{{user.degree}}</h6>
                        <h6 class="fw-normal">Phone: {{user.phone}}</h6>
                        <h6 class="fw-normal">Email: {{user.email}}</h6>
                    </div>

                    <div class="divider"></div>
                    
                    <div class="body">

                        <div class="patient-form-info">
                            
                            <div class="d-flex justify-content-between">
                                <div class="form-info">
                                    <h6>Date: </h6>  <p>{{now}}</p>
                                </div>

                                <div class="form-info">
                                    <h6>P.No: </h6>  <p>{{patient.pno}}</p>
                                </div>
                            </div>
                            
                            <div class="form-info">
                                <h6>Patient Name: </h6>  <p class="flex-grow-1">{{patient.name}}</p>
                            </div>

                            <div class="form-info">
                                <h6>Address: </h6>  <p class="flex-grow-1">{{patient.pAddress}}</p>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <div class="form-info">
                                    <h6>Birthdate: </h6>  <p>{{patient.pBirthdate}}</p>
                                </div>

                                <div class="form-info">
                                    <h6>Gender: </h6>  <p>{{patient.pGender}}</p>
                                </div>
                            </div>
                        </div>

                        <div class="prescription">
                            <h5>Prescribe Medicine</h5>
                            
                            <div class="prescription-box">
                                <div class="medicines" v-for="(data, ind) in medicine" :key="'m'+ind">
                                    <h6> {{data.medicine}} </h6> <div class="divider-vertical"></div>
                                    <h6> {{data.dosage}} </h6> <div class="divider-vertical"></div>
                                    <h6> {{data.perDay}} </h6> <div class="divider-vertical"></div>
                                    <h6> {{data.days}} </h6>
                                </div>
                            </div>

                            <h5>Patient Problem</h5>
                            
                            <div class="prescription-box">
                                
                                <div class="prescription-input">
                                    <h6 class="mb-0"> {{patientProblem}} </h6>
                                </div>
                            </div>
                            
                            <h5>Rx.</h5>
                            
                            <div class="prescription-box">
                                
                                <div class="prescription-input">
                                    <h6 class="mb-0"> {{rX}} </h6>
                                </div>
                            </div>
                            
                            <h5>Advice</h5>
                            
                            <div class="prescription-box">
                                
                                <div class="prescription-input">
                                    <h6 class="mb-0">{{advice}}</h6>
                                </div>
                            </div>

                        </div>
                        
                    </div>

                    <div class="divider"></div>

                    <div class="footer" v-if="patient != ''">
                        <div class="payment p-3">
                            <h5>Payment: </h5>
                            
                            <div class="d-flex align-items-center"  v-for="(payment, ind) in payments" :key="ind+'PP'">
                                <h6 class="mb-0"> {{payment}} </h6> &nbsp;
                            </div>
                            <h5 class="mt-3 text-dark-green"> Total: &#8369; {{totalPayment}} </h5>
                        </div>
                        
                            
                        <div class="signature mt-3 p-3 d-flex flex-column align-items-end" >
                            <img :src="user.signature" alt="" width="200" height="100">

                            <h6 style="margin-bottom: 0px">Signature: &nbsp; <u> Dr. {{user.name}} MD. </u></h6>
                        </div>
                    </div>

                </div>

                <div v-else class="lab-form">
                    
                    <div class="header">
                        <div class="top-info">
                            <h6> LABORATORY TEST REQUEST </h6>
                            <p> Purpose: To be used when requesting testing of biological and potentially pathogenic agents at the Laboratory Services. Please present this form at the Laboratory Testing Center.</p>
                            <p> ( Requested by Dr. {{user.name}} )</p>
                        </div>
                        
                        <div class="d-flex">
                            <div class="patient-box">
                                <h6>Patient's Name: </h6>
                                <p>{{ patient.name }}</p>
                            </div>

                            <div class="patient-box" style="max-width: 80px;">
                                <h6>PID: </h6>
                                <p>{{ patient.pno }}</p>
                            </div>
                        </div>
                        
                        <div class="d-flex">
                            <div class="patient-box">
                                <h6>Patient's Address: </h6>
                                <p>{{ patient.pAddress }}</p>
                            </div>

                            <div class="patient-box" style="max-width: 100px;">
                                <h6>Phone: </h6>
                                <p>{{ patient.pPhone }}</p>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="patient-box" style="max-width: 120px;">
                                <h6>Date of Birth: </h6>
                                <p>{{ patient.pBirthdate }}</p>
                            </div>

                            <div class="patient-box" style="max-width: 100px;">
                                <h6>Gender: </h6>
                                <p>{{ patient.pGender }}</p>
                            </div>

                            <div class="patient-box"></div>

                            <div class="patient-box" style="max-width: 120px;">
                                <h6>Today's Date: </h6>
                                <p>{{ now }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="body">
                        <div v-for="(items, name, ind) in checkedLab"  :key="'checked'+ind">
                            <div class="content-box" v-if="items.length > 0">
                                <div class="top-info"> {{labOption[ind].topInfo}}</div>
                                <div class="info-content">
                                    <div class="d-flex align-items-center" style="width: 300px" v-for="(text, index) in items"  :key="'textinfo'+index">
                                        <div class="checked box "></div>
                                        <p> {{text}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style=" padding: 5px;">

                        <div class="d-flex justify-content-between align-items-end">

                            <div>
                                <h6> LIC: &nbsp; <u>{{user.lic}}</u></h6>
                                <h6> PTR: &nbsp; <u>{{user.ptr}}</u></h6>
                                <h6> S2: &nbsp; <u>{{user.s2}}</u></h6>
                            </div>

                            <div>
                                <img :src="user.signature" alt="" width="200" height="100">

                                <h6 style="margin-bottom: 0px"> Signature: &nbsp; <u>  {{ user.name }} MD. </u></h6>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <template #modal-footer>
                <button v-if="isPrescribed" class="btn btn-success " @click="prescribed()">Prescribe</button>
                <button v-else class="btn btn-success " @click="labForm()">Send Lab Form</button>
            </template>
        </b-modal>

        <div class="inquiry-content">

            <div class="patient-box">

                <div class="patient-pick">
                    <h5> Patient: </h5>

                    <div class="patient-dropdown">

                        <div class="patient-button" @click="toggleDropdown()">
                            <h5>{{name}}</h5>
                            <i class="fa-solid fa-arrow-down"></i>
                        </div>

                        <div class="patient-select" v-show="showBox">
                            <div class="search-bar">
                                <input type="text" v-model="keyword">
                                <button><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>

                            <button v-for="(patient, index) in items" class="box-info border-1" :key="index" @click="pickPatient(index)">
                                <h6>{{patient.name}}</h6>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="patient-info" v-if="patient_id != -1">
                    <img :src="patient.pPhotoUrl" alt="Image does not exist" >

                    <div class="patient-details">
                        <h6>Name: <span class="text-dark-green">{{patient.name}}</span></h6>
                        <h6>Said Problem: <span class="text-dark-green">{{patient.pProblem}}</span></h6>
                        <h6>Phone: <span class="text-dark-green">{{patient.pPhone}}</span></h6>
                    </div>

                </div>


            </div>

            <div class="divider"></div>

            <div class="form-tab">
                <button :class="{ active: isPrescribed }" @click="changeContent(true)"> Prescribe </button>
                <button  :class="{ active: !isPrescribed }"  @click="changeContent(false)"> Lab Request </button>
                <button  class="btn bg-warning" @click="openSignature()"> Change signature </button>
            </div>

            <div class="form-box">
                <div class="form-body">

                    <div v-if="isPrescribed" class="prescribe-form">

                        <div class="header">
                            <img src="/img/logo1.png" alt="Logo" width="90" height="115">

                            <div class="doctor-details">
                                <div class="img"
                                :style="{backgroundImage: 'url(' + (user.photoUrl == '' || user.photoUrl == null ? '/img/avatar.png' : user.photoUrl) + ')',}"
                                ></div>
                                <h4 class="mb-0 fw-bold"> Dr. {{user.name}}</h4>
                            </div>
                            <h6 class="fw-normal">{{user.degree}}</h6>
                            <h6 class="fw-normal">Phone: {{user.phone}}</h6>
                            <h6 class="fw-normal">Email: {{user.email}}</h6>

                            <div class="d-flex justify-content-center">
                                <h6 class="fw-normal" style="margin-bottom: 0px; margin-right: 1rem;">LIC: &nbsp; {{user.lic}}</h6>
                                <h6 class="fw-normal" style="margin-bottom: 0px; margin-right: 1rem;">PTR: &nbsp; {{user.ptr}}</h6>
                                <h6 class="fw-normal" style="margin-bottom: 0px;">S2: &nbsp; {{user.s2}}</h6>
                            </div>

                        </div>

                        <div class="divider"></div>
                        
                        <div class="body">

                            <div class="patient-form-info">
                                
                                <div class="d-flex justify-content-between">
                                    <div class="form-info">
                                        <h6>Date: </h6>  <p>{{now}}</p>
                                    </div>

                                    <div class="form-info">
                                        <h6>P.No: </h6>  <p>{{patient.pno}}</p>
                                    </div>
                                </div>
                                
                                <div class="form-info">
                                    <h6>Patient Name: </h6>  <p class="flex-grow-1">{{patient.name}}</p>
                                </div>

                                <div class="form-info">
                                    <h6>Address: </h6>  <p class="flex-grow-1">{{patient.pAddress}}</p>
                                </div>
                                
                                <div class="d-flex justify-content-between">
                                    <div class="form-info">
                                        <h6>Birthdate: </h6>  <p>{{patient.pBirthdate}}</p>
                                    </div>

                                    <div class="form-info">
                                        <h6>Gender: </h6>  <p>{{patient.pGender}}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="prescription">
                                <h5>Prescribe Medicine</h5>
                                
                                <div class="prescription-box">
                                    <div class="medicines" v-for="(data, ind) in medicine" :key="'m'+ind">
                                        <h6> {{data.medicine}} </h6> <div class="divider-vertical"></div>
                                        <h6> {{data.dosage}} </h6> <div class="divider-vertical"></div>
                                        <h6> {{data.perDay}} </h6> <div class="divider-vertical"></div>
                                        <h6> {{data.days}} </h6>
                                        <button @click="deleteMedicine(ind)" class="btn text-danger btn-sm" style="margin-left: .5rem;"> <i class="fa-solid fa-xmark"></i> </button>
                                    </div>

                                    <div class="prescription-input">
                                        <input v-model="medicineForm.medicine" :class="{'error': medicineErrorForm.medicine}" type="text" placeholder="Medicine"> <div class="divider-vertical"></div>
                                        <input v-model="medicineForm.dosage" :class="{'error': medicineErrorForm.dosage}" type="number" min="0" step="1" placeholder="Dosage">
                                        <select v-model="unit">L, mg, mcg, oz,
                                            <option value="L">L</option>
                                            <option value="mL">mL</option>
                                            <option value="mg">mg</option>
                                            <option value="mcg">mcg</option>
                                            <option value="g">g</option>
                                            <option value="oz">oz</option>
                                        </select>
                                        <div class="divider-vertical"></div>
                                        <input v-model="medicineForm.perDay" :class="{'error': medicineErrorForm.perDay}" type="text" placeholder="How many times a day"> <div class="divider-vertical"></div>
                                        <input v-model="medicineForm.days" :class="{'error': medicineErrorForm.days}" type="text" placeholder="Number of days to take" style="margin-right: .5rem">
                                        <button @click="addMedicine()" class="btn btn-success btn-sm"> <i class="fa-solid fa-plus"></i> </button>
                                    </div>
                                </div>

                                <h5>Patient Problem</h5>
                                
                                <div class="prescription-box">
                                    
                                    <div class="prescription-input">
                                        <input v-model="patientProblem" :class="[{'error': patientProblemError}, 'mt-0 w-100']" type="text" placeholder="Patient Problem">
                                    </div>
                                </div>
                                
                                <h5>Rx.</h5>
                                
                                <div class="prescription-box">
                                    
                                    <div class="prescription-input">
                                         <textarea v-model="rX" :class="[{'error': rXError}, 'mt-0 w-100']" rows="5" style="resize: none;"></textarea>
                                    </div>
                                </div>
                                
                                <h5>Advice</h5>
                                
                                <div class="prescription-box">
                                    
                                    <div class="prescription-input">
                                         <textarea v-model="advice" placeholder="Advice to patient" :class="[{'error': adviceError}, 'mt-0 w-100']" rows="5" style="resize: none;"></textarea>
                                    </div>
                                </div>

                            </div>

                            
                        </div>

                        <div class="divider"></div>

                        <div class="footer" v-if="patient != ''">
                            <div class="payment p-3">
                                <h5>Payment: </h5>
                                
                                <div class="d-flex align-items-center"  v-for="(payment, ind) in payments" :key="ind+'PP'">
                                    <h6 class="mb-0"> {{payment}} </h6> &nbsp;
                                    <b-button size="sm" variant="danger" v-if="ind != 0" @click="deletePayment(ind)"> <i class="fa-solid fa-trash"></i> </b-button>
                                </div>
                                
                                <div class="d-flex"> 
                                    <input type="text" placeholder="Reason" v-model="reason"> &nbsp; - &nbsp;
                                    <input type="number" placeholder="Payment" min="0" step="1" v-model="money"> 
                                    <b-button size="sm" variant="success" @click="addPayment(1)"> Additional </b-button>
                                    <b-button size="sm" variant="danger" @click="addPayment(-1)"> Discount </b-button>
                                </div>
                                <h5 class="mt-3 text-dark-green"> Total: &#8369; {{totalPayment}} </h5>
                            </div>
                            
                            <div class="signature mt-3 p-3 d-flex flex-column align-items-end" >
                                <img :src="user.signature" alt="" width="200" height="100">

                                <h6 style="margin-bottom: 0px">Signature: &nbsp; <u> Dr. {{user.name}} MD. </u></h6>
                            </div>
                        </div>

                    </div>

                    <div v-else class="lab-form">
                        
                        <div class="header">
                            <div class="top-info">
                                <h6> LABORATORY TEST REQUEST </h6>
                                <p> Purpose: To be used when requesting testing of biological and potentially pathogenic agents at the Laboratory Services. Please present this form at the Laboratory Testing Center.</p>
                                <p> ( Requested by Dr. {{user.name}} )</p>
                            </div>
                            
                            <div class="d-flex">
                                <div class="patient-box">
                                    <h6>Patient's Name: </h6>
                                    <p>{{ patient.name }}</p>
                                </div>

                                <div class="patient-box" style="max-width: 80px;">
                                    <h6>PID: </h6>
                                    <p>{{ patient.pno }}</p>
                                </div>
                            </div>
                            
                            <div class="d-flex">
                                <div class="patient-box">
                                    <h6>Patient's Address: </h6>
                                    <p>{{ patient.pAddress }}</p>
                                </div>

                                <div class="patient-box" style="max-width: 120px;">
                                    <h6>Phone: </h6>
                                    <p>{{ patient.pPhone }}</p>
                                </div>
                            </div>

                            <div class="d-flex">
                                <div class="patient-box" style="max-width: 120px;">
                                    <h6>Date of Birth: </h6>
                                    <p>{{ patient.pBirthdate }}</p>
                                </div>

                                <div class="patient-box" style="max-width: 100px;">
                                    <h6>Gender: </h6>
                                    <p>{{ patient.pGender }}</p>
                                </div>

                                <div class="patient-box"></div>

                                <div class="patient-box" style="max-width: 120px;">
                                    <h6>Today's Date: </h6>
                                    <p>{{ now }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="body">

                            <div class="content-box" v-for="(items, i) in labOption" :key="'CONT'+i">

                                <div class="top-info">
                                    <h6> {{items.topInfo}}</h6>
                                </div>

                                <div class="info-content" :style="{ height: items.height +'px' }">
                                    
                                    <!-- loop -->
                                    <div class="form-check" v-for="(option, j) in items.options" :key="items.name+i+j" style="max-width: 200px;">
                                        <input class="form-check-input" type="checkbox" :value="items.name+i+j" :id="items.name+i+j" v-model="labform" @change="toggleCheckedLab(option.text, items.name)" >
                                        <label class="form-check-label" :for="items.name+i+j">
                                            {{option.text}}
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div  v-if="patient != ''" style=" padding: 5px;">

                            <div class="d-flex justify-content-between align-items-end">

                                <div>
                                    <h6> LIC: &nbsp; <u>{{user.lic}}</u></h6>
                                    <h6> PRT: &nbsp; <u>{{user.prt}}</u></h6>
                                    <h6> S2: &nbsp; <u>{{user.s2}}</u></h6>
                                </div>

                                <div>
                                    <img :src="user.signature" alt="" width="200" height="100">

                                    <h6 style="margin-bottom: 0px"> Signature: &nbsp; <u>  {{ user.name }} MD. </u></h6>
                                </div>

                            </div>
                        </div>


                    </div>

                </div>
                
                <button @click="showPreview()" class="btn btn-primary text-white mt-3"> Send to patient </button>
            </div>

            
        </div>
    </div>
</template>

<script>
    import Swal from "sweetalert";

    export default {
        props:['patientData', 'userData', 'now', 'csrf'],

        data() {
            return { 
                file: null,

                dismissSecs: 5,
                dismissCountDown: 0,
                message: '',
                variant: '',

                payments: [],
                reason: '',
                money: 0,
                signature: null,
                
                patients: JSON.parse(this.patientData),
                user: '', 

                name: '<Select a Patient>',
                patient: '',
                patient_id: -1,
                showBox: false,
                body: '',
                subject : '',

                keyword: '',

                isPrescribed: true,
                toggleMedicine: false,
                medicine: [],

                medicineForm: {
                    medicine: '',
                    dosage: '',
                    perDay: '',
                    days: '',
                    prescribed: '',
                },
                unit: 'mL',

                medicineErrorForm: {
                    medicine: false,
                    dosage: false,
                    perDay: false,
                    days: false,
                },

                patientProblem: '',
                patientProblemError: false,
                
                rX: '',
                rXError: false,

                advice: '',
                adviceError: false,

                labOption: [
                    {
                        height: 300,
                        name: 'bloodChem',
                        topInfo: 'Blood Chemistry',
                        options: [
                            { text: 'FBS', value: 'bloodChem1' },
                            { text: 'RBS', value: 'bloodChem2' },
                            { text: '2°PPBS', value: 'bloodChem3' },
                            { text: 'OGCT', value: 'bloodChem4' },
                            { text: 'HBA1c', value: 'bloodChem5' },
                            { text: 'BUN', value: 'bloodChem6' },
                            { text: 'Creatinine', value: 'bloodChem7'},
                            { text: 'Uric Acid', value: 'bloodChem8' },
                            { text: 'Sodium (Na)', value: 'bloodChem9' },
                            { text: 'Potassium (K)', value: 'bloodChem10' },
                            { text: 'Calcium (Ca)', value: 'bloodChem11' },
                            { text: 'Ionized Calcium (iCa)', value: 'bloodChem12' },
                            { text: 'Magnesium (Mg)', value: 'bloodChem13' },
                            { text: 'Inorganic Phosphorus', value: 'bloodChem14' },
                            { text: 'Chloride (Cl)', value: 'bloodChem15' },
                            { text: 'Lipid Profile (Chole, Trig, HDL, LDL)' , value: 'bloodChem16' },
                            { text: 'Cholesterol', value: 'bloodChem17' },
                            { text: 'Triglyceride', value: 'bloodChem18' },
                            { text: 'SGOT', value: 'bloodChem19' },
                            { text: 'SGPT', value: 'bloodChem20' },
                            { text: 'Alk, Phosphatase', value: 'bloodChem21' },
                            { text: 'Protein (Total)', value: 'bloodChem22' },
                            { text: 'Albumin', value: 'bloodChem23' },
                            { text: 'HDL Direct', value: 'bloodChem124' },
                            { text: 'TPAG/A/G Ratio', value: 'bloodChem25' },
                            { text: 'Bilirubin (TB, B1, B2)', value: 'bloodChem26' },
                            { text: 'Amylase', value: 'bloodChem27' },
                            { text: 'Lipase', value: 'bloodChem28' },
                            { text: 'CPK Total', value: 'bloodChem29' },
                            { text: 'CPK MB', value: 'bloodChem30' },
                            { text: 'LDH', value: 'bloodChem31' },
                        ],
                    },

                    {
                        height: 150,
                        name: 'urineFecal',
                        topInfo: 'Urinalysis and Fecalysis',
                        options: [
                            { text: 'Routine Urinalysis' },
                            { text: 'Pregnancy Test' },
                            { text: 'Micral Test' },
                            { text: 'Acetone/Ketone' },
                            { text: 'Pregnancy Test' },
                            { text: 'Urine Total Protein / Creatinine-Ratio' },
                            { text: 'Urine Creatinine Clearance (24h)' },
                            { text: 'Urine Total Protein (24h)' },
                            { text: 'Microalburnin Creatinine Ratio' },
                            { text: 'Routine Fecalysis' },
                            { text: 'Occult Blood' },
                        ],
                    },
                    
                    {
                        height: 325,
                        name: 'hemaImmu',
                        topInfo: 'HEMATOLOGY, IMMUNOLOGY, and TUMOR MARKERS',
                        options: [
                            { text: 'CBC' },
                            { text: 'Platelet Count' },
                            { text: 'Blood Typing w/RH' },
                            { text: 'Prothrombin Time' },
                            { text: 'APTT' },
                            { text: 'Clotting Time' },
                            { text: 'Bleeding Time' },
                            { text: 'ESR' },
                            { text: 'Hemoglobin & Hematocrit' },
                            { text: 'ASO Titer' },
                            { text: 'CRP Screening' },
                            { text: 'CRP Quantitative' },
                            { text: 'FT2' },
                            { text: 'FT4' },
                            { text: 'TSH' },
                            { text: 'T3' },
                            { text: 'T4' },
                            { text: 'HbsAg Screening' },
                            { text: 'HbsAgTiter' },
                            { text: 'Anti-HCV' },
                            { text: 'Anti-Hbs' },
                            { text: 'Anti-Hbc IgG (Total)' },
                            { text: 'Anti-Hbc IgM' },
                            { text: 'Anti-HAV IgG' },
                            { text: 'Anti-HAV IgM' },
                            { text: 'HbeAg' },
                            { text: 'Ferrin' },
                            { text: 'Hepa A, B, C' },
                            { text: 'Hepa B Profile' },
                            { text: 'PSA' },
                            { text: 'CEA' },
                            { text: 'CA-125' },
                            { text: 'CA-19-9' },
                            { text: 'CA-15-3' },
                        ],
                    },

                    {
                        height: 65,
                        name: 'microBiology',
                        topInfo: 'Microbiology',
                        options: [
                            { text: 'Gramstain' },
                            { text: 'Specimen' },
                            { text: 'Culture & Sensitivity' },
                        ],
                    },
                    
                    {
                        height: 220,
                        name: 'cardioVal',
                        topInfo: 'Cardiovascular Examintation',
                        options: [
                            { text: '2D ECHO PLAIN (2DE)' },
                            { text: '2D ECHO DOPPLER (2DE)' },
                            { text: '2D ECHO DOPPLER + SALINE CONTRAST (2DED + SC)' },
                            { text: 'STRESS ECHOCARDIOGRAPHY' },
                            { text: 'TREADMILL EXCERCISE TEST' },
                            { text: 'ECG' },
                            { text: 'RENAL DUPLEX SCAN' },
                            { text: 'CAROTID DUPLEX SCAN' },
                            { text: 'VENOUS DUPLEX SCAN' },
                            { text: 'ARTERIAL DUPLEX SCAN' },
                            { text: '24 HOUR HOLTER MONITORING' },
                            { text: '24 HOUR AMBULATORY BP' },
                            { text: 'ANKLE BRACHIAL INDEX' },
                            { text: 'DVT Screening' },
                            { text: 'ABDOMINAL AORTA DUPLEX SCAN' },
                        ],
                    },
                    
                    {
                        height: 100,
                        name: 'stressProto',
                        topInfo: 'stress protocol',
                        options: [
                            { text: 'KATTUS' },
                            { text: 'MOD. KATTUS' },
                            { text: 'NEPTET' },
                            { text: 'MOD. NEPTET' },
                            { text: 'BRUCE' },
                            { text: 'MOD. BRUCE' },
                        ],
                    },
                     {
                        height: 100,
                        name: 'ultraSound',
                        topInfo: 'Ultrasound',
                        options: [
                            { text: 'WHOLE ABDOMEN' },
                            { text: 'KUB' },
                            { text: 'KUB W/ PROSTATE' },
                            { text: 'THYROID' },
                            { text: 'HEPATO-BILLARY TREE' },

                        ],
                    },
                     {
                        height: 80,
                        name: 'pulmoNary',
                        topInfo: 'Pulmonary Examintation',
                        options: [
                            { text: 'PFT with Bronchodillator Challenge' },
                            { text: 'PFT PLAIN' },
                            { text: 'Sleep Study Screening' },
                        ],
                    },
                     {
                        height: 88,
                        name: 'xRay',
                        topInfo: 'x-ray',
                        options: [
                            { text: 'Chest PA'},
                            { text: 'Chest LAT'},
                            { text: 'Chest PA/LAT'},
                            { text: 'Extrerrities'},
                            { text: 'Thoracolumbar Spine'},
                            { text: 'Lumboscaral Spine'},
                            { text: 'Apicolordotic View'},
                        ],
                    },
                ],
                labform: [],

                checkedLab: {
                    bloodChem: [],
                    urineFecal: [],
                    hemaImmu:[],
                    microBiology:[],
                    cardioVal:[],
                    stressProto: [],
                    ultraSound: [],
                    pulmoNary: [],
                    xRay: [],
                },
            }
        },

        methods: {

            getInd(){

            },
            toggleCheckedLab(text, name){
                let ind = this.checkedLab[name].indexOf(text)

                if(ind > -1) this.checkedLab[name].splice(ind, 1)
                else this.checkedLab[name].push(text)

                console.log(this.checkedLab)
            },

            triggerSignature(){
                this.signature = this.$refs['formFile'].files[0]
            },
            
            changeSignature(){
                if(this.signature == null){
                    Swal({
                        title: 'Error!',
                        text: 'Please Fill up the Form!.',
                        icon: 'error'
                    })
                }
                else{
                    let self = this
                    this.openLoading()
                    
                    const form = new FormData()
                    form.append('signature', this.signature)

                    axios.post('/inquiry/signature', form)
                    .then( function (response){
                        let data = response.data
                        
                        if(data.hasError){
                            Swal({
                                title: 'Error!',
                                text: 'Please refresh the page.',
                                icon: 'error'
                            })
                            self.closeLoading()
                        }
                        else{
                            Swal({
                                title: 'Success!',
                                text: 'Signature has been change!',
                                icon: 'success'
                            })

                            self.user.signature = data.signature
                            self.closeLoading()
                            self.$refs["change-signature"].hide()
                        }
                    })
                    .catch( function (error){
                        console.log(error);
                    });
                }
            },

            addMedicine(){
                let error = {
                    medicine: '',
                    dosage: '',
                    perDay: '',
                    days: '',
                }

                let go = true

                if( this.medicineForm.medicine == '') {
                    error.medicine = true
                    go = false
                }
                if( this.medicineForm.dosage == '') {
                    error.dosage = true
                    go = false
                }
                if( this.medicineForm.perDay == '') {
                    error.perDay = true
                    go = false
                }
                if( this.medicineForm.days == '') {
                    error.days = true
                    go = false
                }

                if(go){
                    this.medicineForm.dosage = this.medicineForm.dosage + " " + this.unit

                    this.medicineForm.prescribed = this.medicineForm.medicine + " | " + this.medicineForm.dosage + " | " + this.medicineForm.perDay + " | " + this.medicineForm.days
                    
                    this.medicine.push(this.medicineForm)
                    this.neutralizeMedForm();
                }
                else
                    this.populateError(error)

            },

            addPayment(type){

                if( this.reason == '' || this.money == 0){
                    Swal({
                        title: 'Error!',
                        text: 'Please Fill up the Form!.',
                        icon: 'error'
                    })
                }
                else{
                    if( type == -1 && this.money > 0)
                        this.money = this.money * type
                    else if( type == 1 && this.money < 0)
                        this.money = this.money * -1
                    this.payments.push(
                        this.reason.trim() + " - " + this.money
                    )
                    
                    this.reason = ''
                    this.money = 0
                }
            },

            deleteMedicine(ind){
                this.medicine.splice(ind, 1)
            },

            deletePayment(ind){
                this.payments.splice(ind, 1)
            },

            changeContent(bool){
                this.isPrescribed = bool
            },

            prescribed(){
                // this.openLoading()
                let self = this

                this.payments.push('Total Payment - ' + this.totalPayment)

                axios.post('/inquiry/prescribe', {
                    medicine: this.medicine,
                    actualProblem: this.patientProblem,
                    rx: this.rX,
                    advice: this.advice,
                    id: this.patient_id,
                    payments: this.payments,
                    appointState: this.patient.appointState,
                    patient: this.patient,
                })
                .then( function (response){
                    let data = response.data
                    
                    if(data.hasError){
                        self.payments.pop()

                        Swal({
                            title: 'Error!',
                            text: 'Please refresh the page.',
                            icon: 'error'
                        })
                        self.closeLoading()
                    }
                    else{
                        self.removeList()
                        
                        Swal({
                            title: 'Success!',
                            text: 'Prescription has been sent to the user.',
                            icon: 'success'
                        })

                        self.closeLoading()
                        self.$refs['preview-modal'].hide()
                    }
                })
                .catch( function (error){
                    console.log(error);
                });
            },

            labForm(){
                // this.openLoading()
                let self = this
                
                axios.post('/inquiry/send', {
                    patient: JSON.stringify(self.patient),
                    now: self.now,
                    checkedLab: JSON.stringify(self.checkedLab),
                })
                .then( function (response){
                    let data = response.data
                    
                    if(data.hasError){
                        Swal({
                            title: 'Error!',
                            text: 'Please refresh the page.',
                            icon: 'error'
                        })
                        self.closeLoading()
                    }
                    else{
                        self.removeList()

                        Swal({
                            title: 'Success!',
                            text: 'Lab request form has been sent to the user.',
                            icon: 'success'
                        })

                        self.closeLoading()
                        self.$refs['preview-modal'].hide()
                    }
                })
                .catch( function (error){
                    console.log(error);
                });
            },

            removeList(){

                let length = this.patients.length
                
                for(let i = 0; i < length; i++){
                    
                    if(this.patients[i].id == this.patient_id){
                        this.patients.splice(i, 1)
                        break;
                    }
                } 

                this.patient_id = -1
                this.patient = ''
                this.medicine = []
                this.rx = ''
                this.advice = '',
                this.patientProblem = ''
                this.payments = []
                this.patient = ''
                this.name = '<Select a Patient>'

                this.neutralizeMedForm()

            },

            pickPatient(ind){
                
                this.patient = this.items[ind]

                if(this.patient.photoUrl != null && this.patient.photoUrl != ''){
                    let sub = this.patient.pPhotoUrl.substring(0, 5).toLowerCase()

                    if(sub != 'https'){
                        this.toLinkImage(this.patient.pPhotoUrl, true, false)
                    }
                }

                this.name = this.patient.name
                this.patient_id = this.patient.id

                this.payments = []
                
                let def = this.patient.appointState == 'Teleconsultation' ? "TeleConsultation Fee - " + this.user.teleconsultFee: "Consultation Fee - " + this.user.consultFee

                this.payments.push(def)

                this.toggleDropdown()
            },

            toLinkImage(image, isPatient, isSign){
                let self = this
                
                axios.post('/inquiry/image', {
                    image: image,
                })
                .then( function (response){
                    let data = response.data
                    
                    if(data.hasError){
                        self.variant = 'danger'
                        self.message = 'Image does not exist in the database'
                        self.showAlert()
                    }
                    else{
                        if(isPatient)
                            self.patient.imageUrl = data.image
                        else if (isSign)
                            self.user.signature = data.image
                        else
                            self.user.photoUrl = data.image
                    }
                })
                .catch( function (error){
                    console.log(error);
                });

            },

            toggleDropdown(){ this.showBox = !this.showBox },
            triggerFile(){ this.file = this.$refs['picture'].files[0] },
            countDownChanged(dismissCountDown) { this.dismissCountDown = dismissCountDown },
            showAlert() { this.dismissCountDown = this.dismissSecs },
            
            showPreview(){
                let text = ''
                let go = true

                if(this.isPrescribed){
                    if(!this.medicine.length){
                        text += '- Recommend a medicine!\n'
                        go = false
                    }
                    if(this.patientProblem == ''){
                        text += '- State the problem of the patient\n'
                        go = false
                    }
                    if(this.totalPayment < 0){
                        text += '- Fix the Total Payment\n'
                        go = false
                    }
                }
                else{
                    if(!this.labform.length){
                        text += '- Checked a type of Lab Request!\n'
                        go = false
                    }
                }

                if(this.patient_id == -1){
                    text += '- Pick a patient!'
                    go = false
                }
                if(this.user.signature == '' || this.user.signature == null){
                    text += '- Add your Signature!\n'
                    go = false
                }

                if(go)
                    this.$refs['preview-modal'].show()
                else{
                    Swal({
                        title: 'Oops! You forgot to ',
                        text: text,
                        icon: 'error'
                    })
                }
            },

            neutralizeMedForm(){
                
                this.medicineForm = {
                    medicine: '',
                    dosage: '',
                    perDay: '',
                    days: '',
                }

                this.medicineErrorForm = {
                    medicine: false,
                    dosage: false,
                    perDay: false,
                    days: false,
                }
            },

            populateError(error){
                
                this.medicineErrorForm = {
                    medicine: error.medicine,
                    dosage: error.dosage,
                    perDay: error.perDay,
                    days: error.days,
                }
            },

            // Loading Modal Related
                openLoading(){ this.$refs['loading-modal'].show() },

                closeLoading(){ this.$refs['loading-modal'].hide() },
            // END

            openSignature(){ this.$refs['change-signature'].show() },
            closeSignature(){ this.$refs['change-signature'].show() },
        },

        computed:{
            items(){
                return this.keyword
                    ? this.patients.filter(
                        item => item.name.toLowerCase().includes(this.keyword.toLowerCase()) 
                    )
                    : this.patients
            },
            
            totalPayment(){
                let total = 0
                let length = this.payments.length
                
                for(let i = 0; i < length ; i++){
                    let payment = this.payments[i]
                    let num = payment.substring( payment.indexOf('-')+1).trim()
                    console.log(num)
                    num = parseFloat(num)

                    total += num
                }

                return total
            },
        },

        mounted(){
            this.user = JSON.parse(this.userData)

            this.user.name = this.user.fname + ' ' + this.user.lname
            
            if(this.user.photoUrl != null && this.user.photoUrl != ''){
                let sub = this.user.photoUrl.substring(0, 5).toLowerCase()

                if(sub != 'https')
                    this.toLinkImage(this.user.photoUrl, false, false)
            }

            if(this.user.signature != null && this.user.signature != ''){
                let sub = this.user.signature.substring(0, 5).toLowerCase()

                if(sub != 'https')
                    this.toLinkImage(this.user.signature, false, true)
            }

        }

    }
</script>