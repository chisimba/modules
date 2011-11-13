/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.rtt.demo.jnlp;

import java.util.List;

/**
 *
 * @author davidwaf
 */
public interface JnlpService {

    public JnlpParam getJnlpParam(String userId, String key);

    public void saveJnlpParam(String userId,String key,String value);

    public void deleteJnlpParams(String userId);
    
    public List<JnlpParam> getJnlpParams(String userId);
}
