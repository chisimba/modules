/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.rtt.demo.jnlp;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import javax.sql.DataSource;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.jdbc.core.JdbcTemplate;
import org.springframework.jdbc.core.RowMapper;
import org.springframework.jdbc.core.simple.SimpleJdbcInsert;

/**
 *
 * @author davidwaf
 */
public class JnlpServiceImpl implements JnlpService {

    private JdbcTemplate jdbcTemplate;
    private SimpleJdbcInsert insertParam;
    protected static SimpleDateFormat dateFormatter = new SimpleDateFormat("yyyy-MM-dd", new Locale("en_US"));

    @Autowired
    public void init(DataSource dataSource) {
        this.jdbcTemplate = new JdbcTemplate(dataSource);
        this.insertParam = new SimpleJdbcInsert(dataSource).withTableName("rttdemo_jnlp");
    }

    @Override
    public void deleteJnlpParams(String userId) {
        String sql =
                "delete from rttdemo_jnlp where username = '" + userId + "'";
        this.jdbcTemplate.execute(sql);
    }

    @Override
    public JnlpParam getJnlpParam(String userId, String key) {
        String sql =
                "select * from rttdemo_jnlp where username  ='" + userId + "' and jnlp_key = '" + key + "'";
        return this.jdbcTemplate.queryForObject(sql, new JnlpParamMapper());
    }

    @Override
    public List<JnlpParam> getJnlpParams(String userId) {
        String sql =
                "select * from rttdemo_jnlp where username  ='" + userId + "'";
        return this.jdbcTemplate.query(sql, new JnlpParamMapper());
    }

    @Override
    public void saveJnlpParam(String userId, String key, String value) {
        Map<String, Object> parameters = new HashMap<String, Object>();
        parameters.put("username", userId);
        parameters.put("jnlp_key", key);
        parameters.put("jnlp_value", value);
        parameters.put("createdon", dateFormatter.format(new Date()));
        this.insertParam.execute(parameters);
    }

    private static final class JnlpParamMapper implements RowMapper<JnlpParam> {

        @Override
        public JnlpParam mapRow(ResultSet rs, int rowNum) throws SQLException {
            JnlpParam jnlpParam = new JnlpParam();
            jnlpParam.setCreatedOn(rs.getString("createdon"));
            jnlpParam.setKey(rs.getString("jnlp_key"));
            jnlpParam.setUserid(rs.getString("username"));
            jnlpParam.setValue(rs.getString("jnlp_value"));
            return jnlpParam;
        }
    }
}
