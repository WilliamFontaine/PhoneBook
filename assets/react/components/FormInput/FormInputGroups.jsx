import React from "react";

import "./FormInput.scss";
import {IoIosCloseCircleOutline} from "react-icons/io";
import {useTranslation} from "react-i18next";

const FormInputGroups = ({
                           label,
                           type,
                           name,
                           allGroups,
                           selectedGroups,
                           currentGroup,
                           inputPlaceholder,
                           selectPlaceholder,
                           onChange,
                           selectInDropdown,
                           handleKeyDown,
                           deleteGroup
                         }) => {
  const {t} = useTranslation();

  return (
    <div className="input-groups">
      <div className="input-field">
        <label htmlFor={name}>{label}</label>

        <div className="input">
          <select id={`${name}-select`} name={name} onChange={selectInDropdown}>
            <option value="">{selectPlaceholder}</option>
            {allGroups.map((group, index) => (
              selectedGroups.includes(group.name) ? null :
                <option value={group.name} key={index}>{group.name}</option>
            ))}
          </select>
          <input type={type}
                 id={name}
                 name={name}
                 onChange={onChange}
                 value={currentGroup}
                 placeholder={inputPlaceholder}
                 onKeyDown={handleKeyDown}/>
        </div>
      </div>
      {selectedGroups.length > 0 &&
        <div className="selected-groups">
          <p>{t(`FormInput.selectedGroups.${selectedGroups.length === 1 ? 'singular' : 'plural'}`)}</p>

          <div className="selected-groups__groups">
            {selectedGroups.map((group, index) => (
              <div className="selected-group" key={index}>
                <p>{group}</p>
                <div className="delete-icon">
                  <IoIosCloseCircleOutline className="icon" onClick={() => deleteGroup(group)}/>
                </div>
              </div>
            ))}
          </div>
        </div>
      }
    </div>
  );
}
export default FormInputGroups;
