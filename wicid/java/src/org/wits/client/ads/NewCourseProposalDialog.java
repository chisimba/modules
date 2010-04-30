/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.wits.client.ads;

import com.extjs.gxt.ui.client.Style.HorizontalAlignment;
import com.extjs.gxt.ui.client.Style.LayoutRegion;
import com.extjs.gxt.ui.client.data.ModelData;
import com.extjs.gxt.ui.client.event.ButtonEvent;
import com.extjs.gxt.ui.client.event.SelectionListener;
import com.extjs.gxt.ui.client.util.Margins;
import com.extjs.gxt.ui.client.widget.Dialog;
import com.extjs.gxt.ui.client.widget.MessageBox;
import com.extjs.gxt.ui.client.widget.Window;
import com.extjs.gxt.ui.client.widget.button.Button;
import com.extjs.gxt.ui.client.widget.form.DateField;
import com.extjs.gxt.ui.client.widget.form.FormPanel;
import com.extjs.gxt.ui.client.widget.form.FormPanel.Encoding;
import com.extjs.gxt.ui.client.widget.form.FormPanel.Method;
import com.extjs.gxt.ui.client.widget.form.NumberField;
import com.extjs.gxt.ui.client.widget.form.Radio;
import com.extjs.gxt.ui.client.widget.form.RadioGroup;
import com.extjs.gxt.ui.client.widget.form.TextArea;
import com.extjs.gxt.ui.client.widget.form.TextField;
import com.extjs.gxt.ui.client.widget.layout.BorderLayout;
import com.extjs.gxt.ui.client.widget.layout.BorderLayoutData;
import com.extjs.gxt.ui.client.widget.layout.FormData;
import com.google.gwt.core.client.GWT;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.RequestException;
import com.google.gwt.http.client.Response;
import com.google.gwt.i18n.client.DateTimeFormat;
import java.util.Date;
import org.wits.client.Constants;
import org.wits.client.TopicListingFrame;
import org.wits.client.ads.OverView;
import org.wits.client.util.WicidXML;

/**
 *
 * @author davidwaf
 */
public class NewCourseProposalDialog {

    private Dialog newDocumentDialog = new Dialog();
    private Dialog facultyListingDialog = new Dialog();
    private FormPanel mainForm = new FormPanel();
    private FormData formData = new FormData("-20");
    private DateTimeFormat fmt = DateTimeFormat.getFormat("y/M/d");
    private final TextField<String> titleField = new TextField<String>();
    private final TextField<String> deptField = new TextField<String>();
    private final NumberField telField = new NumberField();
    private final TextField<String> numberField = new TextField<String>();
    private Button saveButton = new Button("Next");
    private Button browseFacultiesButton = new Button("Browse Faculties");
    private TextArea facultyField = new TextArea();
    private String newCourseProposalDialogData,dept,title,telephone;
    private TopicListingFrame facultyListingFrame;
    private ModelData selectedFolder;
    private OverView oldOverView;
    private boolean status = false;
    private Date date = new Date();

    public NewCourseProposalDialog() {

        createUI();
    }

    public NewCourseProposalDialog(OverView oldOverView) {
        this.oldOverView = oldOverView;
        createUI();
    }

    private void createUI() {

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setWidth(480);

        final DateField dateField = new DateField();
        dateField.setFieldLabel("Entry date");
        /*String date[] = document.getDate().split("/");
        try {
        Calendar cal = new GregorianCalendar(Integer.parseInt(date[0]), Integer.parseInt(date[1])-1, Integer.parseInt(date[2]));
        Date xdate = cal.getTime();
        dateField.setValue(xdate);
        } catch (Exception ex) {
        ex.printStackTrace();
        }*/
        dateField.getPropertyEditor().setFormat(fmt);
        dateField.setName("datefield");
        mainForm.add(dateField, formData);
        dateField.setValue(new Date());
        dateField.setEditable(false);
        dateField.setAllowBlank(false);


        numberField.setFieldLabel("Reference Number");
        numberField.setAllowBlank(false);

        numberField.setName("numberfield");
        //mainForm.add(numberField, formData);


        deptField.setFieldLabel("Originating department");
        deptField.setAllowBlank(false);

        deptField.setName("deptfield");
        mainForm.add(deptField, formData);

        telField.setFieldLabel("Tel. Number");
        telField.setAllowBlank(false);
        telField.setName("telfield");
        telField.setAllowDecimals(false);
        telField.setAllowNegative(false);
        mainForm.add(telField, formData);

        titleField.setFieldLabel("Document title");
        titleField.setAllowBlank(false);
        titleField.setName("titlefield");
        mainForm.add(titleField, formData);
        
        BorderLayoutData centerData = new BorderLayoutData(LayoutRegion.CENTER);
        centerData.setMargins(new Margins(0));

        BorderLayoutData eastData = new BorderLayoutData(LayoutRegion.EAST, 150);
        eastData.setSplit(true);
        eastData.setMargins(new Margins(0, 0, 0, 5));
        facultyField.setName("topic");
        facultyField.setFieldLabel("Faculty");
        facultyField.setReadOnly(true);

        FormPanel panel = new FormPanel();
        panel.setSize(400, 70);
        panel.setHeading("Faculty");
        panel.setLayout(new BorderLayout());
        panel.add(facultyField, centerData);

        browseFacultiesButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                if (facultyListingFrame == null) {
                    facultyListingFrame = new TopicListingFrame(NewCourseProposalDialog.this);
                    facultyListingDialog.setBodyBorder(false);
                    facultyListingDialog.setHeading("Faculty Listing");
                    facultyListingDialog.setWidth(700);
                    facultyListingDialog.setHeight(350);
                    facultyListingDialog.setHideOnButtonClick(true);
                    facultyListingDialog.setButtons(Dialog.OK);
                    facultyListingDialog.setButtonAlign(HorizontalAlignment.CENTER);

                    facultyListingDialog.add(facultyListingFrame);
                }
                facultyListingDialog.show();
            }
        });

        panel.add(browseFacultiesButton, eastData);
        mainForm.add(panel, formData);

        Radio publicOpt = new Radio();
        publicOpt.setBoxLabel("Public");
        publicOpt.setValue(true);

        Radio privateOpt = new Radio();
        privateOpt.setBoxLabel("Private");

        Radio draftOpt = new Radio();
        draftOpt.setBoxLabel("Draft");

        RadioGroup radioGroup = new RadioGroup();
        radioGroup.setFieldLabel("Access");
        radioGroup.add(publicOpt);
        radioGroup.add(privateOpt);
        radioGroup.add(draftOpt);


        saveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {

                
                dept = deptField.getValue();// deptField.getValue().getId();
                title = titleField.getValue();
                telephone = telField.getValue().toString();
                try {
                    if (dateField.getDatePicker() != null) {
                        date = dateField.getDatePicker().getValue();
                    }
                } catch (Exception ex) {
                }

                
                if (dept == null) {
                    MessageBox.info("Missing department", "Provide originating department", null);
                    return;
                }
                if (dept.trim().equals("")) {
                    MessageBox.info("Missing department", "Provide department", null);
                    return;
                }
                
                if (title == null) {
                    MessageBox.info("Missing title", "Provide title", null);
                    return;
                }
                if (title.trim().equals("")) {
                    MessageBox.info("Missing title", "Provide title", null);
                    return;
                }

                
                if (telephone == null) {
                    MessageBox.info("Missing telephone", "Provide telephone", null);
                    return;
                }

                if (telephone.length()>10){
                    MessageBox.info("Wrong telephone number", "The telephone number you provided is too long", null);
                    return;
                }

                if (telephone.length()<5){
                    MessageBox.info("Wrong telephone number", "The telephone number you provided is too short", null);
                    return;
                }
                
                if (selectedFolder == null) {
                    MessageBox.info("Missing faculty", "Please select faculty", null);
                    return;
                }
                storeDocumentInfo();
                
                String url =
                        GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN
                        + "?module=wicid&action=saveFormData&formname=" + "contactdetails" + "&formdata=" + newCourseProposalDialogData + "&docid=" + Constants.docid;

                createDocument(url);

            }
        });
        mainForm.addButton(saveButton);
        mainForm.addButton(saveButton);

        mainForm.setButtonAlign(HorizontalAlignment.LEFT);
        //FormButtonBinding binding = new FormButtonBinding(mainForm);
        //binding.addButton(saveButton);
        newDocumentDialog.setBodyBorder(false);
        newDocumentDialog.setHeading("Document Details");
        newDocumentDialog.setWidth(500);
        newDocumentDialog.setHeight(400);
        newDocumentDialog.setHideOnButtonClick(true);
        newDocumentDialog.setButtons(Dialog.CLOSE);
        newDocumentDialog.setButtonAlign(HorizontalAlignment.LEFT);

        newDocumentDialog.getButtonById(Dialog.CLOSE).addSelectionListener(new SelectionListener<ButtonEvent>() {
            @Override
            public void componentSelected(ButtonEvent ce) {
                storeDocumentInfo();         
            }
        });

        newDocumentDialog.add(mainForm);
        setDepartment();


    }

    public void show() {
        newDocumentDialog.show();
    }

    public void storeDocumentInfo() {  
        String faculty = facultyField.getValue();
       
        WicidXML wicidxml = new WicidXML("data");
        wicidxml.addElement("dept", dept);
        wicidxml.addElement("telnumber", telephone);
        wicidxml.addElement("title", title);
        wicidxml.addElement("faculty", faculty);
        newCourseProposalDialogData = wicidxml.getXml();
        
    }
    
    public void setDocumentInfo(){

    }

    public void setOldOverView(OverView oldOverView) {
        this.oldOverView = oldOverView;
    }

    public TextField<String> getTitleField() {
        return titleField;
    }

    private void setDepartment() {
        String url = GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN + "?module=wicid&action=getdepartment";

        RequestBuilder builder =
                new RequestBuilder(RequestBuilder.GET, url);

        try {
            Request request = builder.sendRequest(null, new RequestCallback() {

                public void onError(Request request, Throwable exception) {
                    MessageBox.info("Error", "Error, cannot create new document", null);
                }

                public void onResponseReceived(Request request, Response response) {
                    if (200 == response.getStatusCode()) {
                        deptField.setValue(response.getText());
                    } else {
                        MessageBox.info("Error", "Error occured on the server. Cannot create document", null);
                    }
                }
            });
        } catch (RequestException e) {
            MessageBox.info("Fatal Error", "Fatal Error: cannot create new document", null);
        }
    }

    private void createDocument(String url) {

        RequestBuilder builder = new RequestBuilder(RequestBuilder.GET, url);

        try {

            Request request = builder.sendRequest(null, new RequestCallback() {

                public void onError(Request request, Throwable exception) {
                    MessageBox.info("Error", "Error, cannot create new document", null);
                }

                public void onResponseReceived(Request request, Response response) {
                    String resp[] = response.getText().split("|");

                    if (resp[0].equals("")) {
                        if (oldOverView == null) {
                            for(int i=0;i<resp.length;i++){
                            Constants.docid = resp[i++];
                            }
                            OverView overView =new OverView(NewCourseProposalDialog.this);
                            overView.show();
                            newDocumentDialog.hide();
                        } else {
                            oldOverView.show();
                            newDocumentDialog.hide();

                        }

                    } else {
                        MessageBox.info("Error", "Error occured on the server. Cannot create document", null);
                    }
                }
            });
        } catch (Exception e) {
            MessageBox.info("Fatal Error", "Fatal Error: cannot create new document", null);
        }

    }

    public void setSelectedFolder(ModelData selectedFolder) {
        this.selectedFolder = selectedFolder;
        facultyField.setValue((String) this.selectedFolder.get("id"));
        facultyField.setToolTip((String) this.selectedFolder.get("id"));
    }
}
