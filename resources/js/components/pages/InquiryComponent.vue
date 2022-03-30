<template>
    <div class="inquiry-page">

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
                        <div class="content-box">

                            <div class="top-info">
                                <h6> CHEMISTRY PROFILE</h6>
                            </div>
                            <div class="info-content">
                                
                                <div v-for="(data, ind) in chemProfOptions " class="d-flex align-items-center" :key="'CP'+ind">
                                    <div :class="[{'checked' : labform.includes(data.value)}, 'box']"></div>
                                    <p>{{ data.text }}</p>
                                </div>
                            </div>

                            <div class="top-info">
                                <h6> CHEMISTRY</h6>
                            </div>
                            
                            <div class="info-content">
                                <div v-for="(data, ind) in chemOptions " class="d-flex align-items-center" :key="'C'+ind">
                                    <div :class="[{'checked' : labform.includes(data.value)}, 'box']"></div>
                                    <p>{{ data.text }}</p>
                                </div>
                            </div>
                            
                            <div class="top-info">
                                <h6> GLUCOSE TOLERANT TEST</h6>
                            </div>

                            <div class="info-content">
                                
                                <div v-for="(data, ind) in glucoseOptions " class="d-flex align-items-center" :key="'G'+ind">
                                    <div :class="[{'checked' : labform.includes(data.value)}, 'box']"></div>
                                    <p>{{ data.text }}</p>
                                </div>

                            </div>
                        </div>

                        <div class="content-box">
                            
                            <div class="top-info">
                                <h6> BODY FLUIDS</h6>
                            </div>
                            <div class="info-content">
                                <div v-for="(data, ind) in bodyFluidsOptions " class="d-flex align-items-center" :key="'BF'+ind">
                                    <div :class="[{'checked' : labform.includes(data.value)}, 'box']"></div>
                                    <p>{{ data.text }}</p>
                                </div>
                            </div>
                            
                            <div class="top-info">
                                <h6> HEMATOLOGY/COAGULATION</h6>
                            </div>
                            <div class="info-content">
                                
                                <div v-for="(data, ind) in hemaOptions " class="d-flex align-items-center" :key="'H'+ind">
                                    <div :class="[{'checked' : labform.includes(data.value)}, 'box']"></div>
                                    <p>{{ data.text }}</p>
                                </div>

                            </div>
                            
                            <div class="top-info">
                                <h6> URINALYSIS</h6>
                            </div>
                            <div class="info-content">
                                <div v-for="(data, ind) in urineOptions " class="d-flex align-items-center" :key="'U'+ind">
                                    <div :class="[{'checked' : labform.includes(data.value)}, 'box']"></div>
                                    <p>{{ data.text }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <template #modal-footer>
                <button v-if="isPrescribed" class="btn btn-success " @click="prescribed()">Prescribe</button>
                <button v-else class="btn btn-success " @click="labForm()">Send Lab Form</button>

                <!-- <form action="/test" v-else method="POST"> 
                    <input type="hidden" name="_token" v-bind:value="csrf">
                    <input type="hidden" name="patient" :value="JSON.stringify(patient)">
                    <input type="hidden" name="now" :value="now">

                    <input type="hidden" name="chemProfOptions" :value="JSON.stringify(chemProfOptions)">
                    <input type="hidden" name="chemOptions" :value="JSON.stringify(chemOptions)">
                    <input type="hidden" name="glucoseOptions" :value="JSON.stringify(glucoseOptions)">
                    <input type="hidden" name="labform" :value="JSON.stringify(labform)">
                    <button class="btn btn-success " >Send Lab</button>
                </form> -->
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
                            <div class="content-box">

                                <div class="top-info">
                                    <h6> CHEMISTRY PROFILE</h6>
                                </div>
                                <div class="info-content">
                                    
                                    <b-form-group>

                                        <b-form-checkbox-group
                                            id="checkbox-group-1"
                                            v-model="labform"
                                            :options="chemProfOptions"
                                            name="chemProfile-1"
                                        ></b-form-checkbox-group>

                                    </b-form-group>

                                </div>

                                <div class="top-info">
                                    <h6> CHEMISTRY</h6>
                                </div>
                                
                                <div class="info-content">
                                    
                                    <b-form-group>

                                        <b-form-checkbox-group
                                            id="checkbox-group-3"
                                            v-model="labform"
                                            :options="chemOptions"
                                            name="chemistry-1"
                                        ></b-form-checkbox-group>

                                    </b-form-group>

                                </div>
                                
                                <div class="top-info">
                                    <h6> GLUCOSE TOLERANT TEST</h6>
                                </div>

                                <div class="info-content">
                                    
                                    <b-form-group>

                                        <b-form-checkbox-group
                                            id="checkbox-group-6"
                                            v-model="labform"
                                            :options="glucoseOptions"
                                            name="glucose-1"
                                        ></b-form-checkbox-group>

                                    </b-form-group>

                                </div>
                            </div>

                            <div class="content-box">
                                
                                <div class="top-info">
                                    <h6> BODY FLUIDS</h6>
                                </div>
                                <div class="info-content">
                                    
                                    <b-form-group>

                                        <b-form-checkbox-group
                                            id="checkbox-group-2"
                                            v-model="labform"
                                            :options="bodyFluidsOptions"
                                            name="bodyFluids-1"
                                        ></b-form-checkbox-group>

                                    </b-form-group>

                                </div>
                                
                                <div class="top-info">
                                    <h6> HEMATOLOGY/COAGULATION</h6>
                                </div>
                                <div class="info-content">
                                    
                                    <b-form-group>

                                        <b-form-checkbox-group
                                            id="checkbox-group-4"
                                            v-model="labform"
                                            :options="hemaOptions"
                                            name="hema-1"
                                        ></b-form-checkbox-group>

                                    </b-form-group>

                                </div>
                                
                                <div class="top-info">
                                    <h6> URINALYSIS</h6>
                                </div>
                                <div class="info-content">
                                    
                                    <b-form-group>

                                        <b-form-checkbox-group
                                            id="checkbox-group-5"
                                            v-model="labform"
                                            :options="urineOptions"
                                            name="urine-1"
                                        ></b-form-checkbox-group>

                                    </b-form-group>

                                </div>

<!--                                 
                                <div class="top-info">
                                    <h6> URINALYSIS</h6>
                                </div>
                                <div class="info-content">
                                    
                                    <b-form-group>

                                        <b-form-checkbox-group
                                            id="checkbox-group-5"
                                            v-model="labform"
                                            :options="urineOptions"
                                            name="urine-1"
                                        ></b-form-checkbox-group>

                                    </b-form-group>

                                </div> -->
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

                chemProfOptions: [
                    { text: 'Panel 1 (Metabolic - Glu, BUN, Create, Na, K, Cl, CO2)', value: 'CP.1' },
                    { text: 'Panel 2 (Liver - T. Prot, Alb, Alk, Phos, LDH, AST, ALT)', value: 'CP.2' },
                    { text: 'Panel 3 (Complete Metabolic Panel - metabolic and liver panel)', value: 'CP.3' },
                    { text: 'Panel 4 (Cardiac - LDH, CK, CK - MB)', value: 'CP.4' },
                    { text: 'Panel 5 (Lipid - Chol, Trig, HDL, LDL)', value: 'CP.5' },
                    { text: 'Panel 6 (Lytes - Na, K, Cl, CO2)', value: 'CP.6' },
                    { text: 'Panel 7 (Urine Lytes, Na, K, Cl, CREA)', value: 'CP.7' },
                ],

                chemOptions: [
                    { text: 'Glucose', value: 'C.1' },
                    { text: 'Acetone', value: 'C.2' },
                    { text: 'BUN', value: 'C.3' },
                    { text: 'Creatinine', value: 'C.4' },
                    { text: 'Uric Acid', value: 'C.5' },
                    { text: 'Potassium', value: 'C.6' },
                    { text: 'Calcium', value: 'C.7' },
                    { text: 'Phosphorus', value: 'C.8' },
                    { text: 'Magnesium', value: 'C.9' },
                    { text: 'Bilirubin, Total', value: 'C.10' },
                    { text: 'Bilirubin, Neonatal', value: 'C.11' },
                ],
                
                hemaOptions: [
                    { text: 'FDP/FSP (Contact Lab for Special Tube)', value: 'H.1' },
                    { text: 'Reticulocyte Count', value: 'H.2' },
                    { text: 'Sedimentation Rate', value: 'H.3' },
                    { text: 'Fibrinogen', value: 'H.4' },
                    { text: 'CBC', value: 'H.5' },
                    { text: 'Differential', value: 'H.6' },
                    { text: 'Hct/Hgb', value: 'H.7' },
                    { text: 'G.6-PD', value: 'H.8' },
                    { text: 'D-Dimmer', value: 'H.9' },
                    { text: 'PT/INR', value: 'H.10' },
                ],

                glucoseOptions: [
                    { text: '2 HR PP', value: 'G.1' },
                    { text: '1 HR Screen (OB)', value: 'G.2' },
                ],

                urineOptions:[
                    { text: 'Routine Urinalysis', value: 'U.1' },
                    { text: 'Microscopic', value: 'U.2' },
                    { text: 'Semen Analysis', value: 'U.3' },
                    { text: 'Glucose', value: 'U.4' },
                    { text: 'Specific Gravity', value: 'U.5' },
                    { text: 'Post Vas', value: 'U.6' },
                ],
                
                bodyFluidsOptions: [
                    { text: 'Glucose', value: 'BF.1' },
                    { text: 'Protein', value: 'BF.2' },
                    { text: 'LDH', value: 'BF.3' },
                    { text: 'Cell Count and Differential', value: 'BF.4' },
                ],
                
                labform: [],
            }
        },

        methods: {

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

            deleteMedicine(ind){
                this.medicine.splice(ind, 1)
            },

            changeContent(bool){
                this.isPrescribed = bool
            },

            prescribed(){
                this.openLoading()
                let self = this
                
                axios.post('/inquiry/prescribe', {
                    medicine: this.medicine,
                    actualProblem: this.patientProblem,
                    rx: this.rX,
                    advice: this.advice,
                    id: this.patient_id,
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
                this.openLoading()
                let self = this
                
                axios.post('/inquiry/send', {
                    patient: JSON.stringify(self.patient),
                    now: self.now,
                    chemProfOptions: JSON.stringify(self.chemProfOptions),
                    chemOptions: JSON.stringify(self.chemOptions),
                    glucoseOptions: JSON.stringify(self.glucoseOptions),

                    hemaOptions: JSON.stringify(self.hemaOptions),
                    urineOptions: JSON.stringify(self.urineOptions),
                    bodyFluidsOptions: JSON.stringify(self.bodyFluidsOptions),

                    labform: JSON.stringify(self.labform),
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
                    }
                } 

                this.patient_id = -1
                this.patient = ''
                this.medicine = []
                this.rx = ''
                this.advice = '',
                this.patientProblem = ''

                this.neutralizeMedForm()

            },

            pickPatient(ind){
                
                this.patient = this.items[ind]

                console.log(this.patient)

                if(this.patient.photoUrl != null && this.patient.photoUrl != ''){
                    let sub = this.patient.pPhotoUrl.substring(0, 5).toLowerCase()

                    if(sub != 'https'){
                        this.toLinkImage(this.patient.pPhotoUrl, true)
                    }
                }

                this.name = this.patient.name
                this.patient_id = this.patient.id

                this.toggleDropdown()
            },

            toLinkImage(image, isPatient){
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
        },

        computed:{
            items(){
                return this.keyword
                    ? this.patients.filter(
                        item => item.name.toLowerCase().includes(this.keyword.toLowerCase()) 
                    )
                    : this.patients
            },
        },

        mounted(){
            this.user = JSON.parse(this.userData)

            this.user.name = this.user.fname + ' ' + this.user.lname
            
            if(this.user.photoUrl != null && this.user.photoUrl != ''){
                let sub = this.user.photoUrl.substring(0, 5).toLowerCase()

                if(sub != 'https')
                    this.toLinkImage(this.user.photoUrl, false)
            }

        }

    }
</script>