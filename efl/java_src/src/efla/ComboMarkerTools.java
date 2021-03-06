/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * ComboMarkerTools.java
 *
 * Created on 2009/10/20, 10:14:48 PM
 */
package efla;

import efla.util.EssayErrorCodes;
import efla.util.HighlightColors;
import java.awt.Color;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.util.ArrayList;

/**
 *
 * @author kim
 */
public class ComboMarkerTools extends javax.swing.JDialog {

    private EflaView eflaView;
    private ArrayList<Integer> codes = new ArrayList<Integer>();
    private ArrayList<Color> colors = new ArrayList<Color>();

    /** Creates new form ComboMarkerTools */
    public ComboMarkerTools(java.awt.Frame parent, boolean modal, EflaView eflaView) {
        super(parent, modal);
        initComponents();
        this.eflaView = eflaView;
        missingWordsOption.setText("Missing words");
        articleErrorsOption.setText("Article errors");
        conjunctionErrorsOption.setText("Conjuction errors");
        grammarErrorsOption.setText("Grammah");
        partOfSpeechErrorsOption.setText("Part of speech");
        prepositionErrorsOption.setText("Preposition");
        punctuationErrorsOption.setText("Punctuation");
        registerStyleErrorsOption.setText("Register/style");
        spellingErrorsOption.setText("Spelling");
        subjectVerbErrorsOption.setText("Subject/Verb");
        tenseErrors.setText("Tenses");
        vocabularyErrorsOption.setText("Vocabulary");
        wordOrderErrorsOption.setText("Word order");
        wrongWordErrorsOption.setText("Wrong word");
        applyButton.setText("Apply");
        closeButton.setText("Close");
        applyButton.addActionListener(new ActionListener() {

            public void actionPerformed(ActionEvent e) {
                applyStyles();
            }
        });

    }

    private void applyStyles() {
        codes.clear();
        colors.clear();
        if (missingWordsOption.isSelected()) {
            codes.add(EssayErrorCodes.MISSING_WORDS);
            colors.add(HighlightColors.MISSING_WORDS);
        }
        if (articleErrorsOption.isSelected()) {
            codes.add(EssayErrorCodes.ARTICLE_ERRORS);
            colors.add(HighlightColors.ARTICLE_ERRORS);
        }
        if (conjunctionErrorsOption.isSelected()) {
            codes.add(EssayErrorCodes.CONJUNCTION_ERRORS);
            colors.add(HighlightColors.CONJUNCTION_ERRORS);
        }
        if (grammarErrorsOption.isSelected()) {
            codes.add(EssayErrorCodes.GRAMMAR_ERRORS);
            colors.add(HighlightColors.GRAMMAR_ERRORS);
        }
        if (punctuationErrorsOption.isSelected()) {
            codes.add(EssayErrorCodes.PUNCTUATION_ERRORS);
            colors.add(HighlightColors.PUNCTUATION_ERRORS);
        }
        if (partOfSpeechErrorsOption.isSelected()) {
            codes.add(EssayErrorCodes.PART_OF_SPEECH_ERRORS);
            colors.add(HighlightColors.PART_OF_SPEECH_ERRORS);
        }
        if (prepositionErrorsOption.isSelected()) {
            codes.add(EssayErrorCodes.PREPOSITION_ERRORS);
            colors.add(HighlightColors.PREPOSITION_ERRORS);
        }
        if (registerStyleErrorsOption.isSelected()) {
            codes.add(EssayErrorCodes.PREPOSITION_ERRORS);
            colors.add(HighlightColors.PREPOSITION_ERRORS);
        }
        if (registerStyleErrorsOption.isSelected()) {
            codes.add(EssayErrorCodes.REGISTER_STYLE_ERRORS);
            colors.add(HighlightColors.REGISTER_STYLE_ERRORS);
        }
        if (spellingErrorsOption.isSelected()) {
            codes.add(EssayErrorCodes.SPELLING_ERRORS);
            colors.add(HighlightColors.SPELLING_ERRORS);
        }
        if (subjectVerbErrorsOption.isSelected()) {
            codes.add(EssayErrorCodes.SUBJECT_VERB_ERRORS);
            colors.add(HighlightColors.SUBJECT_VERB_ERRORS);
        }
        if (tenseErrors.isSelected()) {
            codes.add(EssayErrorCodes.TENSE_ERRORS);
            colors.add(HighlightColors.TENSE_ERRORS);
        }
        if (vocabularyErrorsOption.isSelected()) {
            codes.add(EssayErrorCodes.VOCABULARY_ERRORS);
            colors.add(HighlightColors.VOCABULARY_ERRORS);
        }
        if (wordOrderErrorsOption.isSelected()) {
            codes.add(EssayErrorCodes.WORD_ORDER_ERRORS);
            colors.add(HighlightColors.WORD_ORDER_ERRORS);
        }
        if (wrongWordErrorsOption.isSelected()) {
            codes.add(EssayErrorCodes.WRONG_WORD_ERRORS);
            colors.add(HighlightColors.WRONG_WORD_ERRORS);
        }
        eflaView.highLight(codes, colors);
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        missingWordsOption = new javax.swing.JCheckBox();
        articleErrorsOption = new javax.swing.JCheckBox();
        conjunctionErrorsOption = new javax.swing.JCheckBox();
        grammarErrorsOption = new javax.swing.JCheckBox();
        punctuationErrorsOption = new javax.swing.JCheckBox();
        partOfSpeechErrorsOption = new javax.swing.JCheckBox();
        prepositionErrorsOption = new javax.swing.JCheckBox();
        registerStyleErrorsOption = new javax.swing.JCheckBox();
        spellingErrorsOption = new javax.swing.JCheckBox();
        subjectVerbErrorsOption = new javax.swing.JCheckBox();
        tenseErrors = new javax.swing.JCheckBox();
        vocabularyErrorsOption = new javax.swing.JCheckBox();
        wordOrderErrorsOption = new javax.swing.JCheckBox();
        wrongWordErrorsOption = new javax.swing.JCheckBox();
        applyButton = new javax.swing.JButton();
        closeButton = new javax.swing.JButton();
        jLabel1 = new javax.swing.JLabel();

        setDefaultCloseOperation(javax.swing.WindowConstants.DISPOSE_ON_CLOSE);
        setName("Form"); // NOI18N
        closeButton.setName("closeButton"); // NOI18N
        closeButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                closeButtonActionPerformed(evt);
            }
        });

        jLabel1.setName("jLabel1"); // NOI18N

        org.jdesktop.layout.GroupLayout layout = new org.jdesktop.layout.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
            .add(layout.createSequentialGroup()
                .add(37, 37, 37)
                .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
                    .add(layout.createSequentialGroup()
                        .add(wrongWordErrorsOption)
                        .addContainerGap())
                    .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
                        .add(layout.createSequentialGroup()
                            .add(wordOrderErrorsOption)
                            .addContainerGap())
                        .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
                            .add(layout.createSequentialGroup()
                                .add(vocabularyErrorsOption)
                                .addContainerGap())
                            .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
                                .add(layout.createSequentialGroup()
                                    .add(tenseErrors)
                                    .addContainerGap())
                                .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
                                    .add(layout.createSequentialGroup()
                                        .add(subjectVerbErrorsOption)
                                        .addContainerGap())
                                    .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
                                        .add(layout.createSequentialGroup()
                                            .add(punctuationErrorsOption)
                                            .addContainerGap())
                                        .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
                                            .add(layout.createSequentialGroup()
                                                .add(grammarErrorsOption)
                                                .addContainerGap())
                                            .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
                                                .add(layout.createSequentialGroup()
                                                    .add(conjunctionErrorsOption)
                                                    .addContainerGap())
                                                .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
                                                    .add(layout.createSequentialGroup()
                                                        .add(articleErrorsOption)
                                                        .addContainerGap())
                                                    .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
                                                        .add(layout.createSequentialGroup()
                                                            .add(missingWordsOption)
                                                            .addContainerGap())
                                                        .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
                                                            .add(layout.createSequentialGroup()
                                                                .add(jLabel1)
                                                                .addContainerGap())
                                                            .add(org.jdesktop.layout.GroupLayout.TRAILING, layout.createSequentialGroup()
                                                                .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.TRAILING)
                                                                    .add(layout.createSequentialGroup()
                                                                        .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
                                                                            .add(partOfSpeechErrorsOption)
                                                                            .add(prepositionErrorsOption))
                                                                        .addPreferredGap(org.jdesktop.layout.LayoutStyle.RELATED, 262, Short.MAX_VALUE)
                                                                        .add(applyButton))
                                                                    .add(layout.createSequentialGroup()
                                                                        .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
                                                                            .add(registerStyleErrorsOption)
                                                                            .add(spellingErrorsOption))
                                                                        .addPreferredGap(org.jdesktop.layout.LayoutStyle.RELATED, 266, Short.MAX_VALUE)
                                                                        .add(closeButton)))
                                                                .add(40, 40, 40))))))))))))))
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
            .add(layout.createSequentialGroup()
                .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
                    .add(layout.createSequentialGroup()
                        .add(25, 25, 25)
                        .add(jLabel1)
                        .addPreferredGap(org.jdesktop.layout.LayoutStyle.UNRELATED)
                        .add(missingWordsOption)
                        .addPreferredGap(org.jdesktop.layout.LayoutStyle.RELATED)
                        .add(articleErrorsOption)
                        .addPreferredGap(org.jdesktop.layout.LayoutStyle.RELATED)
                        .add(conjunctionErrorsOption)
                        .addPreferredGap(org.jdesktop.layout.LayoutStyle.RELATED)
                        .add(grammarErrorsOption)
                        .addPreferredGap(org.jdesktop.layout.LayoutStyle.RELATED)
                        .add(punctuationErrorsOption)
                        .add(11, 11, 11)
                        .add(layout.createParallelGroup(org.jdesktop.layout.GroupLayout.LEADING)
                            .add(layout.createSequentialGroup()
                                .add(43, 43, 43)
                                .add(registerStyleErrorsOption)
                                .addPreferredGap(org.jdesktop.layout.LayoutStyle.UNRELATED)
                                .add(spellingErrorsOption)
                                .addPreferredGap(org.jdesktop.layout.LayoutStyle.RELATED)
                                .add(subjectVerbErrorsOption)
                                .addPreferredGap(org.jdesktop.layout.LayoutStyle.RELATED)
                                .add(tenseErrors)
                                .addPreferredGap(org.jdesktop.layout.LayoutStyle.RELATED)
                                .add(vocabularyErrorsOption)
                                .addPreferredGap(org.jdesktop.layout.LayoutStyle.RELATED)
                                .add(wordOrderErrorsOption)
                                .addPreferredGap(org.jdesktop.layout.LayoutStyle.UNRELATED)
                                .add(wrongWordErrorsOption))
                            .add(layout.createSequentialGroup()
                                .add(4, 4, 4)
                                .add(applyButton)
                                .add(18, 18, 18)
                                .add(closeButton))))
                    .add(layout.createSequentialGroup()
                        .add(148, 148, 148)
                        .add(prepositionErrorsOption)
                        .add(5, 5, 5)
                        .add(partOfSpeechErrorsOption)))
                .addContainerGap(58, Short.MAX_VALUE))
        );

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void closeButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_closeButtonActionPerformed
        dispose();
    }//GEN-LAST:event_closeButtonActionPerformed

    private void applyButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_applyButtonActionPerformed
        applyStyles();
    }//GEN-LAST:event_applyButtonActionPerformed
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JButton applyButton;
    private javax.swing.JCheckBox articleErrorsOption;
    private javax.swing.JButton closeButton;
    private javax.swing.JCheckBox conjunctionErrorsOption;
    private javax.swing.JCheckBox grammarErrorsOption;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JCheckBox missingWordsOption;
    private javax.swing.JCheckBox partOfSpeechErrorsOption;
    private javax.swing.JCheckBox prepositionErrorsOption;
    private javax.swing.JCheckBox punctuationErrorsOption;
    private javax.swing.JCheckBox registerStyleErrorsOption;
    private javax.swing.JCheckBox spellingErrorsOption;
    private javax.swing.JCheckBox subjectVerbErrorsOption;
    private javax.swing.JCheckBox tenseErrors;
    private javax.swing.JCheckBox vocabularyErrorsOption;
    private javax.swing.JCheckBox wordOrderErrorsOption;
    private javax.swing.JCheckBox wrongWordErrorsOption;
    // End of variables declaration//GEN-END:variables
}
